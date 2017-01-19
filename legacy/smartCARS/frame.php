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
define('version','official-w3052-3/12/2016');
define('LOAD_TYPE_RANDOM_EDITABLE','randomopen');
define('LOAD_TYPE_RANDOM_LOCKED','randomlocked');
require_once("interface.php");
$dbcreds = smartCARS::getdbcredentials();

$dbConnection;
try {
	$dbConnection = new PDO('mysql:dbname=' . $dbcreds['name'] . ';host=' . $dbcreds['server'] . ';charset=utf8', $dbcreds['user'], $dbcreds['pass']);
}
catch(PDOException $err) {        
	die("Failed to connect to the database.");
} 

function table_structure() {     
	global $dbConnection;
	$param = "CREATE TABLE IF NOT EXISTS smartCARS_sessions ( id int(16) AUTO_INCREMENT, PRIMARY KEY(id), dbid int(16), sessionid varchar(64), timestamp int(16)); CREATE TABLE IF NOT EXISTS smartCARS_charteredflights (routeid int, PRIMARY KEY(routeid), dbid int, bidid int);";
	$stmt  = $dbConnection->prepare($param);
	$stmt->execute();
	$stmt->closeCursor();
	return;
}

function clear_old_sessions() {
	global $dbConnection;
	$stmt = $dbConnection->prepare("DELETE FROM smartCARS_sessions WHERE timestamp < ?");
	$stmt->execute(array(
		time() - 2592000
	));
	$stmt->closeCursor();
}
	
function write_sessid($pilotid, $sessid) {
	global $dbConnection;
	$stmt = $dbConnection->prepare("INSERT INTO smartCARS_sessions (id, dbid, sessionid, timestamp) VALUES (NULL, ?, ?, ?)");
	$stmt->execute(array(
		$pilotid,
		$sessid,
		time()
	));
	$stmt->closeCursor();
}

function check_session($dbid, $sessionid) {
	global $dbConnection;
	$stmt = $dbConnection->prepare("SELECT * FROM smartCARS_sessions WHERE dbid = ? AND sessionid = ?");
	$stmt->execute(array(
		$dbid,
		$sessionid
	));
	$res = $stmt->fetch();
	if($res['dbid'] != "")
		return true;
	return false;
}

$action = $_GET['action'];
switch($action) {
	case "manuallogin":	
		table_structure();		
		clear_old_sessions();		
		$res = smartCARS::manuallogin($_GET['userid'],$_POST['password'],$_GET['sessionid']);
		if($res['result'] == "ok") {
			write_sessid($res['dbid'], $_GET['sessionid']);									
			$res = str_replace(",","",$res);			
			echo($res['dbid'] . "," . $res['code'] . "," . $res['pilotid'] . "," . $_GET['sessionid'] . "," . $res['firstname'] . "," . $res['lastname'] . "," . $res['email'] . "," . $res['ranklevel'] . "," . $res['rankstring']);			
		}
		else {			
			switch($res['result']) {
				case "inactive":
					echo("ACCOUNT_INACTIVE");
					break;
				case "unconfirmed":
					echo("ACCOUNT_UNCONFIRMED");
					break;
				default:
					echo("AUTH_FAILED");
			}					
		}
		break;
	case "automaticlogin":
		table_structure();
		clear_old_sessions();
		$res = smartCARS::automaticlogin($_GET['dbid'],$_GET['oldsessionid'], $_GET['sessionid']);
		if($res['result'] == "ok") {
			write_sessid($res['dbid'], $_GET['sessionid']);									
			$res = str_replace(",","",$res);			
			echo($res['dbid'] . "," . $res['code'] . "," . $res['pilotid'] . "," . $_GET['sessionid'] . "," . $res['firstname'] . "," . $res['lastname'] . "," . $res['email'] . "," . $res['ranklevel'] . "," . $res['rankstring']);			
		}
		else {			
			switch($res['result']) {
				case "inactive":
					echo("ACCOUNT_INACTIVE");
					break;
				case "unconfirmed":
					echo("ACCOUNT_UNCONFIRMED");
					break;
				default:
					echo("AUTH_FAILED");
			}				
		}
		break;
	case "verifysession": //called by the chat server to authenticate users
		$res = smartCARS::verifysession($_GET['dbid'], $_GET['sessionid']);
		if($res['result'] == "SUCCESS") {
			$res = str_replace(",","",$res);
			echo($_GET['sessionid'] . "," . $res['firstname'] . "," . $res['lastname']);
		}
		else
			echo("AUTH_FAILED");
		break;
	case "getpilotcenterdata":
		$res = smartCARS::getpilotcenterdata($_GET['dbid']);
		if($res['totalflights'] != "") {
			$res = str_replace(",","",$res);
			echo($res['totalhours'] . "," . $res['totalflights'] . "," . $res['averagelandingrate'] . "," . $res['totalpireps']);			
		}
		else
			echo("NO_DATA");
		break;
	case "getairports":	
		$res = smartCARS::getairports($_GET['dbid']);
		$runcount = 0;
		foreach($res['airports'] as $apt) {
			if($rc != 0)
				echo(";");
			$apt = str_replace(";","",$apt);
			$apt = str_replace("|","",$apt);
			echo ($apt[$res['format']['id']] . "|" . strtoupper($apt[$res['format']['icao']]) . "|" . $apt[$res['format']['name']] . "|" . $apt[$res['format']['latitude']] . "|" . $apt[$res['format']['longitude']] . "|" . $apt[$res['format']['country']]);
			$rc++;
		}
		break;		
	case "getaircraft":
		$res = smartCARS::getaircraft($_GET['dbid']);
		$runcount = 0;
		foreach($res['aircraft'] as $ac) {
			if($runcount != 0)
				echo(";");
			$ac = str_replace(";","",$ac);
			$ac = str_replace(",","",$ac);
			echo ($ac[$res['format']['id']] . "," . $ac[$res['format']['fullname']] . "," . $ac[$res['format']['icao']] . "," . $ac[$res['format']['registration']] . "," . $ac[$res['format']['maxpassengers']] . "," . $ac[$res['format']['maxcargo']] . "," . $ac[$res['format']['requiredranklevel']]);
			$runcount++;
		}		
		break;
	case "getbidflights":
		$res = smartCARS::getbidflights($_GET['dbid']);
		$runcount = 0;
		if(sizeof($res['schedules']) > 0) {
			$runcount = 0;
			foreach($res['schedules'] as $schedule) {
				if($runcount != 0)
					echo(";");
				$schedule = str_replace(";","",$schedule);
				$schedule = str_replace("|","",$schedule);
				echo($schedule[$res['format']['bidid']] . "|" . $schedule[$res['format']['routeid']] . "|" . $schedule[$res['format']['code']] . "|" . $schedule[$res['format']['flightnumber']] . "|" . $schedule[$res['format']['departureicao']] . "|" . $schedule[$res['format']['arrivalicao']] . "|" . $schedule[$res['format']['route']] . "|" . $schedule[$res['format']['cruisingaltitude']] . "|" . $schedule[$res['format']['aircraft']] . "|" . $schedule[$res['format']['duration']] . "|" . $schedule[$res['format']['departuretime']] . "|" . $schedule[$res['format']['arrivaltime']] . "|" . $schedule[$res['format']['load']] . "|" . $schedule[$res['format']['type']] . "|" . $schedule[$res['format']['daysofweek']]);
				$runcount++;
			}
		}
		else
			echo("NONE");
		break;
	case "bidonflight":
		if(check_session($_GET['dbid'], $_GET['sessionid']) == true) {
			$ret = smartCARS::bidonflight($_GET['dbid'],$_GET['routeid']);
			switch($ret) {
				case 0:
					echo("FLIGHT_BID");
					break;
				case 1:
					echo("FLIGHT_ALREADY_BID");
					break;
				case 2:
					echo("INVALID_ROUTEID");
					break;
			}
		}
		else
			echo("AUTH_FAILED");
		break;
	case "deletebidflight":
		if(check_session($_GET['dbid'], $_GET['sessionid']) == true) {
			smartCARS::deletebidflight($_GET['dbid'],$_GET['bidid']);
			echo("FLIGHT_DELETED");
		}
		else
			echo("AUTH_FAILED");
		break;
	case "searchpireps":		
		$res = smartCARS::searchpireps($_GET['dbid'], $_GET['departureicao'], $_GET['arrivalicao'], $_GET['startdate'], $_GET['enddate'], $_GET['aircraft'], $_GET['status']);
		if(sizeof($res['pireps']) > 0) {
			$runcount = 0;
			foreach($res['pireps'] as $pirep) {
				if($runcount != 0)
					echo(";");
				$pirep = str_replace(";","",$pirep);
				$pirep = str_replace("|","",$pirep);
				echo($pirep[$res['format']['pirepid']] . "|" . $pirep[$res['format']['code']] . "|" . $pirep[$res['format']['flightnumber']] . "|" . $pirep[$res['format']['date']] . "|" . $pirep[$res['format']['departureicao']] . "|" . $pirep[$res['format']['arrivalicao']] . "|" . $pirep[$res['format']['aircraft']]);
				$runcount++;
			}
		}
		else
			echo("NONE");
		break;
	case "getpirepdata":
		$res = smartCARS::getpirepdata($_GET['dbid'], $_GET['pirepid']);
		$res = str_replace(",","",$res);
		echo($res['duration'] . "," . $res['landingrate'] . "," . $res['fuelused'] . "," . $res['status'] . "," . $res['log']);
		break;		
	case "searchflights":
		$res = smartCARS::searchflights($_GET['dbid'], $_GET['departureicao'], $_GET['mintime'], $_GET['maxtime'], $_GET['arrivalicao'], $_GET['aircraft']);
		if(sizeof($res['schedules']) > 0) {
			$runcount = 0;
			foreach($res['schedules'] as $schedule) {
				if($runcount != 0)
					echo(";");
				$schedule = str_replace(";","",$schedule);
				$schedule = str_replace("|","",$schedule);
				echo($schedule[$res['format']['routeid']] . "|" . $schedule[$res['format']['code']] . "|" . $schedule[$res['format']['flightnumber']] . "|" . $schedule[$res['format']['departureicao']] . "|" . $schedule[$res['format']['arrivalicao']] . "|" . $schedule[$res['format']['route']] . "|" . $schedule[$res['format']['cruisingaltitude']] . "|" . $schedule[$res['format']['aircraft']] . "|" . $schedule[$res['format']['flighttime']] . "|" . $schedule[$res['format']['departuretime']] . "|" . $schedule[$res['format']['arrivaltime']] . "|" . $schedule[$res['format']['daysofweek']]);
				$runcount++;
			}
		}
		else
			echo("NONE");
		break;
	case "createflight":
		if(check_session($_GET['dbid'], $_GET['sessionid']) == true) {
			$ret = false;			
			$ret = smartCARS::createflight($_GET['dbid'], $_GET['flightcode'], $_GET['flightnumber'], $_GET['ticketprice'], $_GET['departureicao'], $_GET['arrivalicao'],$_GET['aircraft'], $_GET['flighttype'], $_GET['departuretime'], $_GET['arrivaltime'], $_GET['flighttime'], $_POST['route'], $_GET['cruisealtitude'], $_GET['distance']);			
			if($ret == true)
				echo("SUCCESS");
			else
				echo("ERROR");
		}
		else
			echo("AUTH_FAILED");
		break;
	case "positionreport":
		if(check_session($_GET['dbid'], $_GET['sessionid']) == true) {
			$ret = smartCARS::positionreport($_GET['dbid'],$_GET['flightnumber'],$_GET['latitude'], $_GET['longitude'],$_GET['magneticheading'], $_GET['trueheading'], $_GET['altitude'], $_GET['groundspeed'], $_GET['departureicao'], $_GET['arrivalicao'], $_GET['phase'], $_GET['arrivaltime'], $_GET['departuretime'], $_GET['distanceremaining'], $_POST['route'], $_GET['timeremaining'], $_GET['aircraft'], $_GET['onlinenetwork']);
			if($ret == true)
				echo("SUCCESS");
			else
				echo("ERROR");
		}
		else
			echo("AUTH_FAILED");
		break;
	case "filepirep":
		if(check_session($_GET['dbid'], $_GET['sessionid']) == true) {
			$ret = smartCARS::filepirep($_GET['dbid'], $_GET['code'], $_GET['flightnumber'], $_GET['routeid'], $_GET['bidid'], $_GET['departureicao'], $_GET['arrivalicao'], $_POST['route'], $_GET['aircraft'], $_GET['load'], $_GET['flighttime'], $_GET['landingrate'], $_POST['comments'], $_GET['fuelused'], $_POST['log']);
			if($ret == true)
				echo("SUCCESS");
			else
				echo("ERROR");
		}
		else
			echo("AUTH_FAILED");
		break;
	default:
		echo("Script OK, Frame Version: " . version . ", Interface Version: " . interface_version);
		break;
}
?>
