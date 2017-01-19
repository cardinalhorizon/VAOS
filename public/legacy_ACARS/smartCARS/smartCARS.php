<?php
/*
 * TFDi Design smartCARS Web Interface

 * Version: w3+
 * Server requirements: PHP Data Objects (PDO)

 * This interface script is provided by TFDi Design for the purposes of creating a web-interface between the smartCARS Virtual Flight Tracking Software and virtual airline databases.
 * This interface script is originally property of TFDi Design.
 
 * This file is originally governed by the TFDi Design smartCARS Virtual Airline Operations License, https://tfdidesign.com/legal.php?request=vaol, and the TFDi Design General License, https://tfdidesign.com/legal.php?request=gl

 * This file may be edited and redistributed by third party developers, but TFDi Design assumes no responsibility for support or maintenance on any modified scripts.
 * All third party developers who modify and/or redistribute this file should add their copyright information as well, but are prohibited from removing the original disclaimers.
 * Any third party developers who modify and/or redistribute this file must modify the version number to indicate that this is not an official distribution of the file.
 
 * If you are unsure if you are using an original, unmodified copy of the smartCARS web interface, we recommend obtaining new copies of the files from the TFDi Design website.
 */
 
 /* Interface Information for VAOS
  *
  * General Information
  * -------------------
  * This file can be modified at will to adapt and customize the function of smartCARS to fit your website. Additional information is below to assist in understanding and customizing this file.  
  *
  * How Does This System Work?
  * --------------------------
  * The file "frame.php" is the file that the smartCARS client will request. That file should never be modified - it verifies data, cleans output to avoid parse errors, and handles housekeeping and database connectivity for you.
  * This file has been split into static functions that return a formatted set of data to the frame. The frame then handles outputting it properly for the smartCARS client.
  * To customize this file, simply modify the functions. Remember that for functions that return arrays of data, such as the airport list, remember to match the format array to the table column names.
  *
  * Database ID (DBID) and Pilot ID Information
  * -------------------------------------------
  * The dbid is sent with every request, instead of pilotid - this is to account for systems where the pilot ID is adjustable.
  * For phpVMS, the dbid and pilotid will always be the same, and the PILOTID_OFFSET does not need to be applied to it, as it is the database index.
  * The PILOTID_OFFSET is only accounted for when providing the user their pilot ID, as it is what is shown. The code uses dbid, as does the web system usually.
  *
  * Security/Authentication Information
  * -----------------------------------
  * Functions that require logins/security before they can be accessed have already been accounted for by the frame - no need to authenticate requests here.
  */
/*
require_once('../../legacy/codon.config.php');
require_once('../../legacy/local.config.php');
require_once("../../legacy/common/NavData.class.php");
require_once("../../legacy/common/ACARSData.class.php");
*/

// The boot.php file will boot composer kinda like a mini framework for tools to utilize with VAOS.
require("../boot.php");
require_once (__DIR__."/../vendor/autoload.php");

define('skip_retired_check', false); //should 'retired' (inactive) pilots be able to log in?
define('include_pending_flights_in_stats', false); //should flights that haven't been accepted/rejected yet be used to compute pilot stats in the smartCARS client?
define('interface_version', 'vaos-official-w3052-3/12/2016');
define('TABLE_PREFIX', $_ENV['DB_PREFIX']);
define('WEB_URL', $_ENV['APP_URL']);

class smartCARS {
	static function getdbcredentials() {
		$ret = array();
		$ret['user'] = $_ENV['DB_USERNAME'];
		$ret['pass'] = $_ENV['DB_PASSWORD'];
		$ret['name'] = $_ENV['DB_DATABASE'];
		$ret['server'] = $_ENV['DB_HOST'];
		return $ret;
	}
	
	static function manuallogin($userid, $password, $sessionid) {
		global $dbConnection;
		$ret = array();
		$user = $userid;
		$param = "SELECT * FROM " . TABLE_PREFIX . "users WHERE ";
        if(strpos($user, '@') == false) {
            $param .= "username";
            $user -= PILOTID_OFFSET;
        }
        else
            $param .= "email";
        $param .= " = ?";
		$stmt = $dbConnection->prepare($param);
		$stmt->execute(array($user));
		$res = $stmt->fetch();
		$stmt->closeCursor();

		// Let's let VAOS's API handle all the authentication.

		$client = new GuzzleHttp\Client();

		$res = $client->request('POST', VAOS_URL.'api/1_0/auth', [
			'query' => [
				'format' => 'email'
			],
			'form_params' => [

				'email' => $userid,
				'password' => $password,

			]
		])->getBody();

		$jdec = json_decode($res, true);

		if ($jdec['status'] == '200')
		{
			$ret['pilotid'] = $jdec['user_info']['username'];
			$ret['firstname'] = $jdec['user_info']['first_name'];
			$ret['lastname'] = $jdec['user_info']['last_name'];;
			$ret['email'] = $jdec['user_info']['email'];
			$ret['ranklevel'] = 0; //$res['ranklevel'];
			$ret['rankstring'] = "N/A"; //$res['rank'];
			$ret['result'] = "ok";
		}
		/*
		if($res['username'] != "") {
			if(skip_retired_check == false) {
				if($res['retired'] != "0") {
					$ret['result'] = "inactive";
					return $ret;
				}
			}
			if($res['confirmed'] == "0") {
				$ret['result'] = "unconfirmed";
				return $ret;					
			}
			// Laravel Authentication uses bcrypt instead of MD5. How nice??
			$hash = bcrypt($password);
			if($hash == $res['password']) {
				$ret['dbid'] = $res['username'];
				$ret['code'] = $res['code'];
				
				$newpltid = $res['username'] + PILOTID_OFFSET;
				$var = "";
				for($i = strlen($newpltid); $i < PILOTID_LENGTH; $i++)
					$var .= "0";				
				$ret['pilotid'] = $var . $newpltid;
				
				$ret['pilotid'] = $res['username'] + PILOTID_OFFSET;
				$ret['firstname'] = $res['first_name'];
				$ret['lastname'] = $res['last_name'];
				$ret['email'] = $res['email'];
				$ret['ranklevel'] = 0; //$res['ranklevel'];
				$ret['rankstring'] = "N/A"; //$res['rank'];
				$ret['result'] = "ok";
			}                 
			else
				$ret['result'] = "failed";
		} */
		else
			$ret['result'] = "failed";

		return $ret;
	}
	
	static function automaticlogin($dbid, $oldsessionid, $sessionid) {
		$ret = array();
		global $dbConnection;
		$stmt = $dbConnection->prepare("SELECT * FROM smartCARS_sessions WHERE dbid = ? AND sessionid = ?");
		$stmt->execute(array(
			$dbid,
			$oldsessionid
		));
		$res1 = $stmt->fetch();
		$stmt->closeCursor();
		if($res1['dbid'] != "") {
			$stmt = $dbConnection->prepare("SELECT * FROM " . TABLE_PREFIX . "users WHERE username = ?");
			$stmt->execute(array($dbid));
			$res = $stmt->fetch();
			$stmt->closeCursor();
			if($res['username'] != "") {
				/*
				if(skip_retired_check == false) {
					if($res['retired'] != "0") {
						$ret['result'] = "inactive";
						return $ret;
					}
				}

				if($res['confirmed'] == "0") {
					$ret['result'] = "unconfirmed";
					return $ret;					
				}
				*/
				$ret['dbid'] = $res['username'];
				$ret['code'] = $res['code'];
				
				$newpltid = $res['username'] + PILOTID_OFFSET;
				$var = "";
				for($i = strlen($newpltid); $i < PILOTID_LENGTH; $i++)
					$var .= "0";				
				$ret['pilotid'] = $var . $newpltid;
				
				$ret['pilotid'] = $res['username'] + PILOTID_OFFSET;
				$ret['firstname'] = $res['first_name'];
				$ret['lastname'] = $res['last_name'];
				$ret['email'] = $res['email'];
				$ret['ranklevel'] = 0; //$res['ranklevel'];
				$ret['rankstring'] = "N/A"; //$res['rank'];
				$ret['result'] = "ok";
			}
			else
				$ret['result'] = "failed";
		}
		else
			$ret['result'] = "failed";
		return $ret;
	}
	
	static function verifysession($dbid, $sessionid) {
		$ret = array();

		global $dbConnection;
		$stmt = $dbConnection->prepare("SELECT * FROM smartCARS_sessions WHERE dbid = ? AND sessionid = ?");
		$stmt->execute(array(
			$dbid,
			$sessionid
		));
		$res1 = $stmt->fetch();
		$stmt->closeCursor();
		if($res1['dbid'] != "") {
			$stmt = $dbConnection->prepare("SELECT * FROM " . TABLE_PREFIX . "users WHERE username = ?");
			$stmt->execute(array($dbid));
			$res = $stmt->fetch();
			$stmt->closeCursor();
			if($res['username'] != "" && $res['retired'] == "0" && $res['confirmed'] != "0") {
				$ret['result'] = "SUCCESS";
				$ret['firstname'] = $res['first_name'];
				$ret['lastname'] = $res['last_name'];
				return $ret;
			}
			else {
				$ret['result'] = "FAILED";
				return $ret;
			}			
		}
		else {
			$ret['result'] = "FAILED";
			return $ret;
		}
	}
	
	static function getpilotcenterdata($dbid) {
		global $dbConnection;
		$ret = array();
		$stmt = $dbConnection->prepare("SELECT * FROM " . TABLE_PREFIX . "users WHERE username = ?");
		$stmt->execute(array($dbid));
		$res = $stmt->fetch();
		$stmt->closeCursor();
		$ret = array();
		if($res['username'] != "") {
			$ret['totalhours'] = "N/A"; //$res['totalhours'];
			$ret['totalflights'] = "N/A"; //$res['totalflights'];
			/*
			if($res['totalflights'] > 0) {
				$stmt = $dbConnection->prepare("SELECT landingrate FROM " . TABLE_PREFIX . "pireps WHERE pilotid = ?" . (include_pending_flights_in_stats == false ? "AND accepted = 1" : "AND accepted != 2") . " ORDER BY submitdate");
				$stmt->execute(array($dbid));
				$pireps = $stmt->fetchAll();
				$stmt->closeCursor();				
				$total_landing = 0;
				$sizeofpireps = sizeof($pireps);
				foreach($pireps as $pirep) {
					$total_landing += $pirep['landingrate'];
				}
				if($sizeofpireps > 0)
					$ret['averagelandingrate'] = round($total_landing / $sizeofpireps);
				else
					$ret['averagelandingrate'] = "0";
				$ret['totalpireps'] = $sizeofpireps;
			}
			else {
			*/
				$ret['averagelandingrate'] = "N/A";			
				$ret['totalpireps'] = "0";
			/*
			}
			*/

		}
		return $ret;
	}
	
	static function getairports($dbid) {
		global $dbConnection;
		$ret = array();
		$stmt = $dbConnection->prepare("SELECT * FROM " . TABLE_PREFIX . "airports ORDER BY icao");
		$stmt->execute();
		$ret['airports'] = $stmt->fetchAll();
		$stmt->closeCursor();
		$ret['format'] = array();
		$ret['format']['id'] = 'id';
		$ret['format']['icao'] = 'icao';
		$ret['format']['name'] = 'name';
		$ret['format']['latitude'] = 'lat';
		$ret['format']['longitude'] = 'lon';
		$ret['format']['country'] = 'country';
		return $ret;
	}
	
	static function getaircraft($dbid) {
		global $dbConnection;
		$res = array();
		$stmt = $dbConnection->prepare("SELECT * FROM " . TABLE_PREFIX . "aircraft WHERE enabled != 0 ORDER BY name");
		$stmt->execute();
		$res['aircraft'] = $stmt->fetchAll();
		$stmt->closeCursor();
		$res['format'] = array();
		$res['format']['id'] = 'id';
		$res['format']['fullname'] = 'name';
		$res['format']['icao'] = 'icao';
		$res['format']['registration'] = 'registration';
		$res['format']['maxpassengers'] = 'maxpax';
		$res['format']['maxcargo'] = 'maxgw';
		$res['format']['requiredranklevel'] = '0';
		return $res;
	}
	
	static function getbidflights($dbid) {
		$client = new GuzzleHttp\Client();

		$res = $client->request('GET', VAOS_URL.'api/1_0/schedule/bid', [
			'query' => [
				'auth' => 'session',
				'client' => 'smartCARS2',
				'userid' => $dbid
			]
		])->getBody();

		return $jdec = json_decode($res, true);

		/*
		global $dbConnection;
		$stmt = $dbConnection->prepare("SELECT * FROM " . TABLE_PREFIX . "bids WHERE user_id = ?");
		$stmt->execute(array($dbid));
		$bids = $stmt->fetchAll();
		$stmt->closeCursor();
		$ret = array();
		$ret['format'] = array();
		$ret['format']['bidid'] = 'bidid';
		$ret['format']['routeid'] = 'id';
		$ret['format']['code'] = 'code';
		$ret['format']['flightnumber'] = 'flightnum';
		$ret['format']['type'] = 'flighttype';
		$ret['format']['departureicao'] = 'depicao';
		$ret['format']['arrivalicao'] = 'arricao';
		$ret['format']['route'] = 'route';
		$ret['format']['cruisingaltitude'] = 'flightlevel';
		$ret['format']['aircraft'] = 'aircraft';
		$ret['format']['duration'] = 'flighttime';
		$ret['format']['departuretime'] = 'deptime';
		$ret['format']['arrivaltime'] = 'arrtime';
		$ret['format']['load'] = 'load';
		$ret['format']['type'] = 'flighttype';
		$ret['format']['daysofweek'] = 'daysofweek';
		$ret['schedules'] = array();


		foreach($bids as $bid) {
			/*
			$stmt = $dbConnection->prepare("SELECT * FROM " . TABLE_PREFIX . "schedules WHERE id = ?");
			$stmt->execute(array($bid['routeid']));
			$schedule = $stmt->fetch();
			$stmt->closeCursor();

			if($schedule['id'] != "") {
				$schedule['bidid'] = $bid['bidid'];
				//How the 'load' value works:
				//You can give a number that will be used as the passenger or cargo number in the client and it will not be editable by the user.
				//You can specify LOAD_TYPE_RANDOM_LOCKED that will generate a random number on the client side but will not allow editing.
				//You can specify LOAD_TYPE_RANDOM_EDITABLE that will function like smartCARS 1.X - generate a random number and allow the user to change it.				
				$continue = false;
				if($schedule['enabled'] != "0")
					$continue = true;
				else {
					$stmt = $dbConnection->prepare("SELECT * FROM smartCARS_charteredflights WHERE routeid = ? AND dbid = ?");
					$stmt->execute(array($bid['routeid'], $dbid));
					$cschedule = $stmt->fetch();
					$stmt->closeCursor();
					if($cschedule['routeid'] != "")
						$continue = true;
				}
				
				$schedule['load'] = LOAD_TYPE_RANDOM_EDITABLE;
				array_push($ret['schedules'],$schedule);
			}
		}
		*/
		// return $ret;
	}
	
	static function bidonflight($dbid, $routeid) {
		global $dbConnection;
		$stmt = $dbConnection->prepare("SELECT * FROM " . TABLE_PREFIX . "schedules WHERE id = ? AND enabled != 0");
		$stmt->execute(array($routeid));
		$schedule = $stmt->fetch();
		$stmt->closeCursor();
		if($schedule['id'] != "") {
			if(DISABLE_BIDS_ON_BID == true) {
				$stmt = $dbConnection->prepare("SELECT * FROM " . TABLE_PREFIX . "bids WHERE routeid = ?");
				$stmt->execute(array($routeid));
				$bid = $stmt->fetch();
				$stmt->closeCursor();
				if($bid['bidid'] != "")
					return 1;
			}
			$stmt = $dbConnection->prepare("INSERT INTO " . TABLE_PREFIX . "bids (userid, routeid, dateadded) VALUES (?, ?, NOW())");
			$stmt->execute(array($dbid, $routeid));
			$stmt->closeCursor();
			return 0;
		}
		return 2;
	}
	
	static function deletebidflight($dbid, $bidid) {
		global $dbConnection;
		$stmt = $dbConnection->prepare("DELETE FROM " . TABLE_PREFIX . "bids WHERE bidid = ? LIMIT 1");
		$stmt->execute(array($bidid));
		$stmt->closeCursor();
		
		$stmt = $dbConnection->prepare("SELECT * FROM smartCARS_charteredflights WHERE bidid = ? AND dbid = ?");
		$stmt->execute(array($bidid, $dbid));
		$crow = $stmt->fetch();
		$stmt->closeCursor();
		if($crow['routeid'] != "") {
			$stmt = $dbConnection->prepare("DELETE FROM smartCARS_charteredflights WHERE bidid = ? AND dbid = ?");
			$stmt->execute(array($bidid, $dbid));
			$stmt->closeCursor();
			
			$stmt = $dbConnection->prepare("DELETE FROM " . TABLE_PREFIX . "schedules WHERE id = ?");
			$stmt->execute(array($crow['routeid']));
			$stmt->closeCursor();
		}
	}
	
	static function _helper_reorder_date_to_mmddyyyy($source) {
		$source = substr($source, 0, 10);
		$yyyymmdd = explode("-", $source);
		$mmddyyyy = $yyyymmdd[1] . "/" . $yyyymmdd[2] . "/" . $yyyymmdd[0];
		return $mmddyyyy;
	}
	
	static function searchpireps($dbid, $departureicao, $arrivalicao, $startdate, $enddate, $aircraft, $status) {
		global $dbConnection;
		$param = "SELECT pirepid, code, submitdate, flightnum, depicao, arricao, aircraft FROM " . TABLE_PREFIX . "pireps WHERE userid = :userid";
		$arg = array();
		$arg[':userid'] = $dbid;
		if($departureicao != "" || $arrivalicao != "" || $startdate != "" || $enddate != "") {
			if ($departureicao != "" && $arrivalicao == "") {
                $param = $param . " AND depicao = :departure";
                $arg[':departure'] = $departureicao;
            }
            else if ($arrivalicao != "" && $departureicao == "") {
                $param = $param . " AND arricao = :arrival";
                $arg[':arrival'] = $arrivalicao;
            }
            else if ($arrivalicao != "" && $departureicao != "") {
                $arg[':departure'] = $departureicao;
                $arg[':arrival'] = $arrivalicao;
                $param = $param . " AND depicao = :departure AND arricao = :arrival";
            }

			if ($startdate != "") {
                $param = $param . " AND submitdate >= :date1";
                $arg[':date1'] = $startdate;
            }
            if ($enddate != "") {
                $param = $param . " AND submitdate <= :date2";
                $arg[':date2'] = $enddate;
            }
		}
		
		if($status != "" && ($status == "1" || $status == "2" || $status == "3")) {
			$param .= " AND accepted = :status";
			if($status == "1") //accepted
				$arg[':status'] = $status;
			else if($status == "2") //pending
				$arg[':status'] = "0";
			else if($status == "3") //rejected
				$arg[':status'] = "2";
		}		
		
		$use_ac = false;
        $valid_aircraft = array();
        if($aircraft != "") {			
            $stmt = $dbConnection->prepare("SELECT * FROM " . TABLE_PREFIX . "aircraft WHERE fullname = ?");
            $stmt->execute(array($aircraft));
			$acdatar = $stmt->fetchAll();
			$stmt->closeCursor();		
			if(sizeof($acdatar) > 0) {
				$use_ac = true;
				foreach($acdatar as $row) {
					array_push($valid_aircraft, $row['id']);
	            }  
			}
        }
		
		if($use_ac == true) {
			$first = true;
			$acc = 0;
			foreach($valid_aircraft as $ac) {
				if($first == true) {
					$param .= " AND aircraft = :ac" . $acc;
					$arg[':ac' . $acc] = $ac;
					$acc++;
				}
				else {
					$param .= " OR aircraft = :ac" . $acc;
					$arg[':ac' . $acc] = $ac;
					$acc++;
				}					
				$first = false;
			}
		}		
		
		$stmt = $dbConnection->prepare($param);
        $stmt->execute($arg);
        $pireps = $stmt->fetchAll();
        $stmt->closeCursor();
		
		$ret = array();
		$ret['format'] = array();
		$ret['pireps'] = array();
		$ret['format']['pirepid'] = "pirepid";
		$ret['format']['code'] = "code";
		$ret['format']['flightnumber'] = "flightnum";
		$ret['format']['departureicao'] = "depicao";
		$ret['format']['date'] = "submitdate";
		$ret['format']['arrivalicao'] = "arricao";
		$ret['format']['arrivalicao'] = "arricao";
		$ret['format']['aircraft'] = "aircraft";		
		foreach($pireps as $key => $pirep) {
			$pireps[$key]['submitdate'] = smartCARS::_helper_reorder_date_to_mmddyyyy($pirep['submitdate']);
		}
		$ret['pireps'] = $pireps;
		return $ret;
				
	}
	
	static function getpirepdata($dbid, $pirepid) {
		global $dbConnection;
		$stmt = $dbConnection->prepare("SELECT * FROM " . TABLE_PREFIX . "pireps WHERE pirepid = ?");
		$stmt->execute(array($pirepid));
		$res = $stmt->fetch();
		$stmt->closeCursor();		

		$ret = array();
		$ret['duration'] = $res['flighttime'];
		$ret['landingrate'] = $res['landingrate'];
		$ret['fuelused'] = $res['fuelused'];		
		$status = "0";
		if($res['accepted'] == "1")
			$status = "1";
		else if($res['accepted'] == "2")
			$status = "2";			
		$ret['status'] = $status;
		$ret['log'] = $res['log'];
		
		return $ret;
	}
	
	static function searchflights($dbid, $departureicao, $mintime, $maxtime, $arrivalicao, $aircraft) {
		global $dbConnection;
        if ($departureicao != "" || $arrivalicao != "" || $mintime != "" || $maxtime != "") {
            $param = "SELECT * FROM " . TABLE_PREFIX . "schedules";
            $arg = array();
            if ($departureicao != "" && $arrivalicao == "") {
                $param = $param . " WHERE depicao = :departure";
                $arg[':departure'] = $departureicao;
            }
            else if ($arrivalicao != "" && $departureicao == "") {
                $param = $param . " WHERE arricao = :arrival";
                $arg[':arrival'] = $arrivalicao;
            }
            else if ($arrivalicao != "" && $departureicao != "") {
                $arg[':departure'] = $departureicao;
                $arg[':arrival'] = $arrivalicao;
                $param = $param . " WHERE depicao = :departure AND arricao = :arrival";
            }
            else
                $param = $param . " WHERE";
            if ($mintime != "") {
                if ($departureicao != "" || $arrivalicao != "")
                    $param = $param . " AND";
                $param = $param . " flighttime >= :time1";
                $arg[':time1'] = $mintime;
            }
            if ($maxtime != "") {
                if ($mintime != "" || $departureicao != "" || $arrivalicao != "")
                    $param = $param . " AND";
                $param = $param . " flighttime >= :time2";
                $arg[':time2'] = $maxtime;
            }
			$param .= " AND enabled != 0";
        }
        else
            $param = "SELECT * FROM " . TABLE_PREFIX . "schedules WHERE enabled != 0";		
		$use_ac = false;
        $valid_aircraft = array();
        if($aircraft != "") {			
            $stmt = $dbConnection->prepare("SELECT * FROM " . TABLE_PREFIX . "aircraft WHERE name = ?");
            $stmt->execute(array($aircraft));
			$acdatar = $stmt->fetchAll();
			$stmt->closeCursor();		
			if(sizeof($acdatar) > 0) {
				$use_ac = true;
				foreach($acdatar as $row) {
					array_push($valid_aircraft, $row['id']);
	            }  
			}
        }
		
		if($use_ac == true) {
			$first = true;
			$acc = 0;
			foreach($valid_aircraft as $ac) {
				if($first == true) {
					$param .= " AND (aircraft = :ac" . $acc;
					$arg[':ac' . $acc] = $ac;
					$acc++;
				}
				else {
					$param .= " OR aircraft = :ac" . $acc;
					$arg[':ac' . $acc] = $ac;
					$acc++;
				}					
				$first = false;
			}
            if($acc > 0)
                $param .= ")";
		}
			
		$param .= " ORDER BY code, flightnum LIMIT 1001";
        $stmt = $dbConnection->prepare($param);
        $stmt->execute($arg);
        $flights = $stmt->fetchAll();
        $stmt->closeCursor();
		
		$ret = array();
		$ret['format'] = array();
		$ret['format']['routeid'] = 'id';
		$ret['format']['code'] = 'code';
		$ret['format']['flightnumber'] = 'flightnum';
		$ret['format']['type'] = 'P';
		$ret['format']['departureicao'] = 'depicao';
		$ret['format']['arrivalicao'] = 'arricao';
		$ret['format']['route'] = 'route';
		$ret['format']['cruisingaltitude'] = 'flightlevel'; //'flightlevel';
		$ret['format']['aircraft'] = 'aircraft';
		$ret['format']['flighttime'] = "0";//'flighttime';
		$ret['format']['departuretime'] = "0";// 'deptime';
		$ret['format']['arrivaltime'] = "0"; //'arrtime';
		$ret['format']['daysofweek'] = 'daysofweek';
		$ret['schedules'] = $flights;
		return $ret;
	}	
	
	static function createflight($dbid, $flightcode, $flightnumber, $ticketprice, $depicao, $arricao, $aircraft, $flighttype, $deptime, $arrtime, $flighttime, $route, $cruisealtitude, $distance) {
		global $dbConnection;
	
		$type = "P";
		if($flighttype == "1")
			$type = "C";
			
		if($flightcode == '')
			$flightcode = 'SCC';
			
		$stmt = $dbConnection->prepare("SELECT * " . TABLE_PREFIX . "airlines WHERE code = ?");		
		$stmt->execute(array(
			$flightcode
		));
		$res = $stmt->fetch();
		$stmt->closeCursor();
		
		if($res['id'] == "") {
			$stmt = $dbConnection->prepare("INSERT INTO " . TABLE_PREFIX . "airlines (id, code, name, enabled) VALUES (NULL, ?, 'Charter', 0)");
			$stmt->execute(array(
				$flightcode
			));
			$stmt->closeCursor();
		}
		
		$stmt = $dbConnection->prepare("INSERT INTO " . TABLE_PREFIX . "schedules (id, code, flightnum, depicao, arricao, route, aircraft, flightlevel, distance, deptime, arrtime, flighttime, price, flighttype, enabled) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");		
		$stmt->execute(array(
			$flightcode,
			$flightnumber,
			$depicao,
			$arricao,
			$route,
			$aircraft,
			$cruisealtitude,
			$distance,
			$deptime,
			$arrtime,
			$flighttime,
			$ticketprice,
			$type
		));		
		$routeid = $dbConnection->lastInsertID();
		$stmt->closeCursor();
		
		$stmt = $dbConnection->prepare("INSERT INTO " . TABLE_PREFIX . "bids (bidid, pilotid, routeid, dateadded) VALUES (NULL, ?, ?, NOW())");
		$stmt->execute(array(
			$dbid,
			$routeid
		));		
		$bidid = $dbConnection->lastInsertID();
		$stmt->closeCursor();
		
		$stmt = $dbConnection->prepare("INSERT INTO smartCARS_charteredflights (routeid, dbid, bidid) VALUES (?, ?, ?)");
		$stmt->execute(array(
			$routeid,
			$dbid,
			$bidid
		));		
		$stmt->closeCursor();
		
		return true;		
	}
	
	static function positionreport($dbid, $flightnumber, $latitude, $longitude, $magneticheading, $trueheading, $altitude, $groundspeed, $departureicao, $arrivalicao, $phase, $arrivaltime, $departuretime, $distanceremaining, $route, $timeremaining, $aircraft, $onlinenetwork) {

		global $dbConnection;
        $stmt = $dbConnection->prepare("SELECT * FROM " . TABLE_PREFIX . "users WHERE username = ?");
		$stmt->execute(array($dbid));
		$pilotdet = $stmt->fetch();
		$stmt->closeCursor();
        
        $phases = array(
			"Preflight",
			"Pushing Back",
			"Taxiing to Runway",
			"Taking Off",
			"Climbing",
			"Cruising",
			"Descending",
			"Approaching",
			"Final Approach",
			"Landing",
			"Taxiing to Gate",
            "Awaiting Arrival", // An intermediary state when smartCARS has detected the aircraft is ready to arrive but the pilot hasn't clicked "End Flight" yet.
			"Arrived"
		);
        
        $lat = str_replace(",", ".", $latitude);
        $lon = str_replace(",", ".", $longitude);
        
        $lat = doubleval($lat);
        $lon = doubleval($lon);
        
        if($lon < 0.005 && $lon > -0.005)
            $lon = 0;
            
        if($lat < 0.005 && $lat > -0.005)
            $lat = 0;        
        
        $fields = array(
            'userid' =>$dbid,
            'flightnum' =>$flightnumber,
            'pilotname' => $pilotdet['first_name'] . " " . $pilotdet['last_name'],
            'aircraft' => $aircraft,
            'lat' =>$lat,
            'lng' =>$lon,
            'heading' =>$magneticheading,
            'alt' =>$altitude,
            'gs' =>$groundspeed,
            'depicao' =>$departureicao,
            'arricao' =>$arrivalicao,
            'deptime' =>$departuretime,
            'arrtime' =>$arrivaltime,
            'route' =>$route,
            'distremain' =>$distanceremaining,
            'timeremaining' =>$timeremaining,
            'phasedetail' =>$phases[$phase],
            'online' => $onlinenetwork,
            'client' =>'smartCARS',
        );
        $client = new GuzzleHttp\Client();

		$ret = $client->request('POST', VAOS_URL.'api/v1_0/acars/position', [
			'query' => [
				'format' => 'phpVMS'
			],
			'form_params' => $fields
			])->getBody();

		$jdec = json_decode($ret, true);

		if ($jdec['status'] = 200)
        	return true;
		else
			return false;
	}	
	static function filepirep($dbid, $code, $flightnumber, $routeid, $bidid, $departureicao, $arrivalicao, $route, $aircraft, $load, $flighttime, $landingrate, $comments, $fuelused, $log) {
		global $dbConnection;
		$log = str_replace('[', '*[', $log);
		$log = str_replace('\\r', '', $log);
		$log = str_replace('\\n', '', $log);
		$pirepdata = array(
            'userid' => $dbid,
            'code' => $code,
            'flightnum' => $flightnumber,
            'depicao' => $departureicao,
            'arricao' => $arrivalicao,
            'route' => $route,
            'aircraft' => $aircraft,
            'load' => $load,
            'flighttime' => $flighttime,
            'landingrate' => $landingrate,
            'submitdate' => date('Y-m-d H:i:s'),
            'comment' => $comments,
            'fuelused' => $fuelused,
            'source' => 'smartCARS',
            'log' => $log
        );

		// Call the main phpVMS_Core API directly to submit our PIREP request.

		$client = new GuzzleHttp\Client();

		$ret = $client->request('POST', VAOS_URL.'api/v1_0/pireps', [
			'query' => [
				'auth' => 'session'
			],

			'form_params' => [

				'userid' => $dbid,
				'code' => $code,
				'flightnum' => $flightnumber,
				'depicao' => $departureicao,
				'arricao' => $arrivalicao,
				'route' => $route,
				'aircraft' => $aircraft,
				'load' => $load,
				'flighttime' => $flighttime,
				'landingrate' => $landingrate,
				'submitdate' => date('Y-m-d H:i:s'),
				'comment' => $comments,
				'fuelused' => $fuelused,
				'source' => 'smartCARS',
				'log' => $log

			]
		])->getBody();

		// $ret = null; //ACARSData::FilePIREP($dbid, $pirepdata);
		
        if(!$ret)
            return false;
		
		$stmt = $dbConnection->prepare("SELECT * FROM smartCARS_charteredflights WHERE bidid = ? AND dbid = ?");
		$stmt->execute(array($bidid, $dbid));
		$crow = $stmt->fetch();
		$stmt->closeCursor();
		if($crow['routeid'] != "") {
			$stmt = $dbConnection->prepare("DELETE FROM smartCARS_charteredflights WHERE bidid = ? AND dbid = ?");
			$stmt->execute(array($bidid, $dbid));
			$stmt->closeCursor();
			
			$stmt = $dbConnection->prepare("DELETE FROM " . TABLE_PREFIX . "schedules WHERE id = ?");
			$stmt->execute(array($crow['routeid']));
			$stmt->closeCursor();
		}
		/*
		$stmt = $dbConnection->prepare("UPDATE " . TABLE_PREFIX . "pilots SET retired = 0 WHERE pilotid = ?");
		$stmt->execute(array($dbid));		    
		*/
		$stmt = $dbConnection->prepare("UPDATE " . TABLE_PREFIX . "acarsdata SET gs = 0, distremain = 0, timeremaining = '0:00', phasedetail = 'Arrived', arrtime = CURRENT_TIMESTAMP WHERE pilotid = ?");
		$stmt->execute(array($dbid));		    

		$stmt = $dbConnection->prepare("DELETE FROM " . TABLE_PREFIX . "bids WHERE pilotid = ? AND bidid = ?");
		$stmt->execute(array($dbid, $bidid));
		return true;
	}
}
 
?>
