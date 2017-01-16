<?php

/**
 * phpVMS ACARS integration
 *
 * Interface for use with XACARS
 * http://www.xacars.net/
 * 
 * 
 * This file goes as this:
 *	The URL given is:
 *		<site>/index.php/acars/xacars/<action>
 * 
 * SDK Docs: http://www.xacars.net/index.php?Client-Server-Protocol
 */

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 'on');
	
Debug::log($_SERVER['QUERY_STRING'], 'xacars');
Debug::log($_SERVER['REQUEST_URI'], 'xacars');
Debug::log(serialize($_REQUEST), 'xacars');

class Coords {
	public $lat;
	public $lng;
}

switch($acars_action)
{
	
	/* Request data about a flight */
	case 'data':
	
		$flight = $_REQUEST['DATA2'];
		Debug::log('FLIGHT PLAN REQUEST', 'xacars');
		
		# They requested latest bid
		if(strtolower($flight) == 'bid')
		{
			/*preg_match('/^([A-Za-z]*)(\d*)/', $_REQUEST['DATA4'], $matches);
			$code = $matches[1];
			$pilotid = intval($matches[2]) - Config::Get('PILOTID_OFFSET');*/
			$pilotid = PilotData::parsePilotID($_REQUEST['DATA4']);
		
			$route = SchedulesData::GetLatestBid($pilotid);
			
			if(!$route)
			{
				echo '0|No bids found!';
				return;
			}
		}
		else
		{
			if(is_numeric($flight))
			{
				echo '0|No airline code entered!';
				return;
			}
			
			$flightinfo = SchedulesData::getProperFlightNum($flight);
			$code = $flightinfo['code'];
			$flight_num = $flightinfo['flightnum'];
			
			$route = SchedulesData::GetScheduleByFlight($code, $flight_num);
			
			Debug::log(print_r($route, true), 'xacars');
			
			if(!$route)
			{
				echo '0|Flight not found, make sure you include the flight code!';
				return;
			}
		}
		
		/* Ok to proceed */
		if($route->flighttype=='H')
		{
			$maxpax = $route->maxpax;
		}
		else
		{
			if($route->flighttype=='C')
			{
				$maxcargo = FinanceData::getLoadCount($route->aircraftid, 'C');
			}
			else
			{
				$maxpax = FinanceData::getLoadCount($route->aircraftid, 'P');
			}
		}
		
		echo
"1|flightplan
$route->depicao
$route->arricao
$route->arricao
$route->route
$maxpax
$maxcargo
IFR
$route->registration
$route->flightlevel
";
		
		break;
				
	case 'acars':
	case 'xacars':
	
		# Pass success by default
		$outstring = 'Success';
		$fields = array();
			
		$_REQUEST['DATA2'] = strtoupper($_REQUEST['DATA2']);	
		if($_REQUEST['DATA2'] == 'TEST')
		{
			echo '1|OK';
			return;
		}
		elseif($_REQUEST['DATA2'] == 'ENDFLIGHT')
		{
			echo '1|OK';
			return;
		}
		elseif($_REQUEST['DATA2'] == 'PAUSEFLIGHT')
		{
			echo '1|OK';
			return;
		}
		elseif($_REQUEST['DATA2'] == 'BEGINFLIGHT')
		{
			/*	
			VMA001||VMW5421|N123K5||KORD~~KMIA|N51 28.3151 W0 26.8892|88||||59|328|00000|14|IFR|0||
			*/
			Debug::log('BEGINFLIGHT', 'xacars');
			$data = explode('|', $_REQUEST['DATA3']);
			
			/* Get the pilot info */
			/*preg_match('/^([A-Za-z]*)(\d*)/', $data[0], $matches);
			$code = $matches[1];
			$pilotid = intval($matches[2]) - Config::Get('PILOTID_OFFSET');*/
			$pilotid = PilotData::parsePilotID($data[0]);
			
			/* Get Coordinates */
			$coords = Util::get_coordinates($data[6]);
						
			/* Get route */
			$route = explode('~', $data[5]);
			$depicao = $route[0];
			$arricao = $route[count($route)-1];
			
			/*	Unset the start and end points of the route,
				and pass the rest in.
				
				@version 2.1
			*/
			unset($route[0]);
			unset($route[count($route)-1]);
			$route = implode(' ', $route);
			
			$flightnum = $data[2];
			$aircraft = $data[3];
			$heading = $data[12];
			$alt = $data[7];
			$deptime = time();
			
			$fields = array(
				'flightnum'=>$flightnum,
				'aircraft'=>$aircraft,
				'lat'=>$coords['lat'],
				'lng'=>$coords['lng'],
				'heading'=>$heading,
				'route'=>$route,
				'alt'=>$alt,
				'gs'=>$gs,
				'depicao'=>$depicao,
				'arricao'=>$arricao,
				'deptime'=>$deptime,
				'phasedetail'=>'At the gate',
				'online'=>$_GET['Online'],
				'client'=>'xacars',
			);
			
			Debug::log(print_r($fields, true), 'xacars');
			
			$outstring = $pilotid;			
		}
		elseif($_REQUEST['DATA2'] == 'MESSAGE')
		{
			$data = $_REQUEST['DATA4'];
			$pilotid = $_REQUEST['DATA3'];
			
			/* Get the flight information, from ACARS, need to
				pull the latest flight data via the flight number
				since acars messages don't transmit the pilot ID */
			
			preg_match("/Flight ID:.(.*)\n/", $data, $matches);
			
			$flight_data = ACARSData::get_flight_by_pilot($pilotid);
			
			Debug::log('Flight data:', 'xacars');
			Debug::log(print_r($_REQUEST, true), 'xacars');
			Debug::log('PilotID: '.$pilotid, 'xacars');

			// Get coordinates from ACARS message
			preg_match("/POS(.*)\n/", $data, $matches);
			$coords = Util::get_coordinates(trim($matches[1]));
			
			// Get our heading
			preg_match("/\/HDG.(.*)\n/", $data, $matches);
			$heading = $matches[1];
			
			// Get our altitude
			preg_match("/\/ALT.(.*)\n/", $data, $matches);
			$alt = $matches[1];
			
			// Get our  speed
			preg_match("/\/IAS.(.*)\//", $data, $matches);
			$gs = $matches[1];
			
			// Get the OUT time
			preg_match("/OUT.(.*) \/ZFW/", $data, $matches);
			$deptime = $matches[1];
			
			/*	We don't need to update every field, just a few of them
			 */
			$fields = array(
				'lat'=>$coords['lat'],
				'lng'=>$coords['lng'],
				'heading'=>$heading,
				'alt'=>$alt,
				'gs'=>$gs,
				'phasedetail'=>'Enroute',
			);
		}
		else
		{
			return;
		}
		
		# Get the distance remaining
		$depapt = OperationsData::GetAirportInfo($depicao);
		$dist_remain = SchedulesData::distanceBetweenPoints($coords->lat, $coords->lng, $depapt->lat, $depapt->lng);
		
		# Estimate the time remaining
		if($gs > 0)
		{
			$time_remain = $dist_remain / $gs;
		}
		else
		{
			$time_remain = '00:00';
		}

		ob_start();
		
		$fields['distremain'] = $dist_remain;
		$fields['timeremaining'] = $time_remain;
		
		Debug::log(print_r($fields, true), 'xacars');
		ACARSData::UpdateFlightData($pilotid, $fields);
		
		echo '1|'.$outstring;
		break;
		
	case 'pirep':
		
		$data = explode('~', $_REQUEST['DATA2']);
	
		$flightinfo = SchedulesData::getProperFlightNum($data[2]);
		$code = $flightinfo['code'];
		$flightnum = $flightinfo['flightnum'];
		
		/*if(!is_numeric($data[0]))
		{
			# see if they are a valid pilot:
			preg_match('/^([A-Za-z]*)(\d*)/', $data[0], $matches);
			$pilot_code = $matches[1];
			$pilotid = intval($matches[2]) - Config::Get('PILOTID_OFFSET');
		}
		else
		{
			$pilotid = $data[0];
		}*/
		$pilotid = PilotData::parsePilotID($data[0]);
		
		# Make sure airports exist:
		#  If not, add them.
		$depicao = $data[6];
		$arricao = $data[7];
		
		if(!OperationsData::GetAirportInfo($depicao))
		{
			OperationsData::RetrieveAirportInfo($depicao);
		}
		
		if(!OperationsData::GetAirportInfo($arricao))
		{
			OperationsData::RetrieveAirportInfo($arricao);
		}
		
		# Get aircraft information
		$reg = trim($data[3]);
		$ac = OperationsData::GetAircraftByReg($reg);
		
		# Load info
		/* If no passengers set, then set it to the cargo */
		$load = $data[14];
		if(empty($load))
			$load = $data[15];
		
		# Convert the time to xx.xx 
		$flighttime = floatval(str_replace(':', '.', $data[11])) * 1.00;
		
		/* Fuel conversion - XAcars only reports in lbs */
		$fuelused = $data[12];
		if(Config::Get('LiquidUnit') == '0')
		{
			# Convert to KGs, divide by density since d = mass * volume
			$fuelused = ($fuelused * .45359237) / .8075;
		}
		# Convert lbs to gallons
		elseif(Config::Get('LiquidUnit') == '1')
		{
			$fuelused = $fuelused * 6.84;
		}
		# Convert lbs to kgs
		elseif(Config::Get('LiquidUnit') == '2')
		{
			$fuelused = $fuelused * .45359237;
		}
		
		$acars_data = ACARSData::get_flight_by_pilot($pilotid);
		
		$data = array(
			'pilotid' => $pilotid,
			'code' => $code,
			'flightnum' => $flightnum,
			'depicao' => $depicao,
			'arricao' => $arricao,
			'aircraft' => $ac->id,
			'flighttime' => $flighttime,
			'submitdate' => 'NOW()',
			'route' => $acars_data->route,
			'route_details' => $acars_data->route_details,
			'comment' => $comment,
			'fuelused' => $fuelused,
			'source' => 'xacars',
			'load' => $load,
			'log' => $_GET['log']
		);
				
		Debug::log(print_r($data, true), 'xacars');
		
		$ret = ACARSData::FilePIREP($pilotid, $data);
				
		echo '1|Success';
		break;
}