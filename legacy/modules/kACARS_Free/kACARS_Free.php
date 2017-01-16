<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Jeffrey Kobus
 * @copyright Copyright (c) 2010, Jeffrey Kobus
 * @link http://www.fs-products.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @ v1.0.1.1
 */



class kACARS_Free extends CodonModule
{	
	
	public function index()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{ 
			
			// Site Settings
			$logTime 	= 0;     		// kACARS_Free FlightLog Timesatmp 0=System Time  or  1=FS Time
			$logPause	= 0;			// kACARS_Free Pause Log 0=Log Pauses or 1=Do NOT Log Pauses
			$version 	= '1.0.1.1';   	// kACARS_Free Version	
			$forceOut 	= 1;          	// Force disconnect is wrong version 0=no 1=yes	
			$charter	= 1;			// Allow Charter flights to be flown (Includes abilty to change aircraft) 0=no 1=yes
			
			
			$postText = file_get_contents('php://input');			
			$encoding = mb_detect_encoding($postText);
			$rec_xml = trim(iconv($encoding, "UTF-8", $postText));			
			$xml = simplexml_load_string($rec_xml);		
			
			if(!$xml)
			{
				#$this->log("Invalid XML Sent: \n".$rec_xml, 'kacars');
				echo "not xml";
				return;	
			}
			
			#$this->log(print_r($xml->asXML(), true), 'kacars');
			
			$case = strtolower($xml->switch->data);
			switch($case)
			{				
				case 'verify':		
					$results = Auth::ProcessLogin($xml->verify->pilotID, $xml->verify->password);		
					if ($results)
					{						
						$params = array('loginStatus' => '1');
					}
					else
					{
						$params = array('loginStatus' => '0');
					}
					
					// Send Site Settings
					$params['logTimeSetting'] 	= $logTime;
					$params['logPauseSetting']	= $logPause;
					$params['version'] 			= $version;
					$params['forceOut'] 		= $forceOut;
					$params['charter']			= $charter;
					
					$send = self::sendXML($params);
					
					break;
				
				case 'getbid':							
					
					$pilotid = PilotData::parsePilotID($xml->verify->pilotID);
					$pilotinfo = PilotData::getPilotData($pilotid);
					$biddata = SchedulesData::getLatestBid($pilotid);
					$aircraftinfo = OperationsData::getAircraftByReg($biddata->registration);
					
					if(count($biddata) == 1)
					{		
						if($aircraftinfo->enabled == 1)
						{
							$params = array(
								'flightStatus' 	   => '1',
								'flightNumber'     => $biddata->code.$biddata->flightnum,
								'aircraftReg'      => $biddata->registration,
								'aircraftICAO'     => $aircraftinfo->icao,
								'aircraftFullName' => $aircraftinfo->fullname,
								'flightLevel'      => $biddata->flightlevel,
								'aircraftMaxPax'   => $aircraftinfo->maxpax,
								'aircraftCargo'    => $aircraftinfo->maxcargo,
								'depICAO'          => $biddata->depicao,
								'arrICAO'          => $biddata->arricao,
								'route'            => $biddata->route,
								'depTime'          => $biddata->deptime,
								'arrTime'          => $biddata->arrtime,
								'flightTime'       => $biddata->flighttime,
								'flightType'       => $biddata->flighttype,
								'aircraftName'     => $aircraftinfo->name,
								'aircraftRange'    => $aircraftinfo->range,
								'aircraftWeight'   => $aircraftinfo->weight,
								'aircraftCruise'   => $aircraftinfo->cruise
								);					
						}
						else
						{	
							$params = array(
								'flightStatus' 	   => '3');		// Aircraft Out of Service.							
						}			
					}		
					else		
					{
						$params = array('flightStatus' => '2');	// You have no bids!								
					}
					
					$send = $this->sendXML($params);
					
					break;
				
				case 'getflight':
					
					$flightinfo = SchedulesData::getProperFlightNum($xml->pirep->flightNumber);
					
					$params = array(
						's.code' => $flightinfo['code'],
						's.flightnum' => $flightinfo['flightnum'],
						's.enabled' => 1,
					);
					
					$biddata = SchedulesData::findSchedules($params, 1);
					$aircraftinfo = OperationsData::getAircraftByReg($biddata[0]->registration);
					
					if(count($biddata) == 1)
					{		
						$params = array(
							'flightStatus' 	   => '1',
							'flightNumber'     => $biddata[0]->code.$biddata[0]->flightnum,
							'aircraftReg'      => $biddata[0]->registration,
							'aircraftICAO'     => $aircraftinfo->icao,
							'aircraftFullName' => $aircraftinfo->fullname,
							'flightLevel'      => $biddata[0]->flightlevel,
							'aircraftMaxPax'   => $aircraftinfo->maxpax,
							'aircraftCargo'    => $aircraftinfo->maxcargo,
							'depICAO'          => $biddata[0]->depicao,
							'arrICAO'          => $biddata[0]->arricao,
							'route'            => $biddata[0]->route,
							'depTime'          => $biddata[0]->deptime,
							'arrTime'          => $biddata[0]->arrtime,
							'flightTime'       => $biddata[0]->flighttime,
							'flightType'       => $biddata[0]->flighttype,
							'aircraftName'     => $aircraftinfo->name,
							'aircraftRange'    => $aircraftinfo->range,
							'aircraftWeight'   => $aircraftinfo->weight,
							'aircraftCruise'   => $aircraftinfo->cruise
							);
					}			
					else		
					{	
						$params = array('flightStatus' 	   => '2');								
					}
					
					$send = $this->sendXML($params);
					break;			
				
				case 'liveupdate':	
					
					$pilotid = PilotData::parsePilotID($xml->verify->pilotID);
					$lat = str_replace(",", ".", $xml->liveupdate->latitude);
					$lon = str_replace(",", ".", $xml->liveupdate->longitude);
					
					# Get the distance remaining
					$depapt = OperationsData::GetAirportInfo($xml->liveupdate->depICAO);
					$arrapt = OperationsData::GetAirportInfo($xml->liveupdate->arrICAO);
					$dist_remain = round(SchedulesData::distanceBetweenPoints(
						$lat, $lon,	$arrapt->lat, $arrapt->lng));
					
					# Estimate the time remaining
					if($xml->liveupdate->groundSpeed > 0)
					{
						$Minutes = round($dist_remain / $xml->liveupdate->groundSpeed * 60);
						$time_remain = self::ConvertMinutes2Hours($Minutes);
					}
					else
					{
						$time_remain = '00:00';
					}					
					
					$fields = array(
						'pilotid'        =>$pilotid,
						'flightnum'      =>$xml->liveupdate->flightNumber,
						'pilotname'      =>'',
						'aircraft'       =>$xml->liveupdate->registration,
						'lat'            =>$lat,
						'lng'            =>$lon,
						'heading'        =>$xml->liveupdate->heading,
						'alt'            =>$xml->liveupdate->altitude,
						'gs'             =>$xml->liveupdate->groundSpeed,
						'depicao'        =>$xml->liveupdate->depICAO,
						'arricao'        =>$xml->liveupdate->arrICAO,
						'deptime'        =>$xml->liveupdate->depTime,
						'arrtime'        =>'',
						'route'          =>$xml->liveupdate->route,
						'distremain'     =>$dist_remain,
						'timeremaining'  =>$time_remain,
						'phasedetail'    =>$xml->liveupdate->status,
						'online'         =>'',
						'client'         =>'kACARS',
						);
					
					#$this->log("UpdateFlightData: \n".print_r($fields, true), 'kacars');
					ACARSData::UpdateFlightData($pilotid, $fields);	
					
					break;
				
				case 'pirep':						
					
					$flightinfo = SchedulesData::getProperFlightNum($xml->pirep->flightNumber);
					$code = $flightinfo['code'];
					$flightnum = $flightinfo['flightnum'];
					
					$pilotid = PilotData::parsePilotID($xml->verify->pilotID);
					
					# Make sure airports exist:
					#  If not, add them.
					
					if(!OperationsData::GetAirportInfo($xml->pirep->depICAO))
					{
						OperationsData::RetrieveAirportInfo($xml->pirep->depICAO);
					}
					
					if(!OperationsData::GetAirportInfo($xml->pirep->arrICAO))
					{
						OperationsData::RetrieveAirportInfo($xml->pirep->arrICAO);
					}
					
					# Get aircraft information
					$reg = trim($xml->pirep->registration);
					$ac = OperationsData::GetAircraftByReg($reg);
					
					# Load info
					/* If no passengers set, then set it to the cargo */
					$load = $xml->pirep->pax;
					if(empty($load))
						$load = $xml->pirep->cargo;						
					
					/* Fuel conversion - kAcars only reports in lbs */
					$fuelused = $xml->pirep->fuelUsed;
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
					
					$data = array(
						'pilotid'			=>$pilotid,
						'code'				=>$code,
						'flightnum'			=>$flightnum,
						'depicao'			=>$xml->pirep->depICAO,
						'arricao'			=>$xml->pirep->arrICAO,
						'aircraft'			=>$ac->id,
						'flighttime'		=>$xml->pirep->flightTime,
						'flighttype'		=>$xml->pirep->flightType,
						'submitdate'		=>'UTC_TIMESTAMP()',
						'comment'			=>$xml->pirep->comments,
						'fuelused'			=>$fuelused,
						'route'          	=>$xml->liveupdate->route,
						'source'			=>'kACARS',
						'load'				=>$load,
						'landingrate'		=>$xml->pirep->landing,
						'log'				=>$xml->pirep->log
					);
					
					#$this->log("File PIREP: \n".print_r($data, true), 'kacars');
					$ret = ACARSData::FilePIREP($pilotid, $data);		
					
					if ($ret)
					{
						$params = array(
							'pirepStatus' 	   => '1');	// Pirep Filed!							
					}
					else
					{
						$params = array(
							'pirepStatus' 	   => '2');	// Please Try Again!							
						
					}
					$send = $this->sendXML($params);						
					
					break;	
				
				case 'aircraft':
					
					$this->getAllAircraft();
					break;
					
				case 'aircraftinfo':
						
						$aircraftinfo = OperationsData::getAircraftByReg($xml->pirep->registration);
			
						
							$params = array(								
								'aircraftReg'      => $aircraftinfo->registration,
								'aircraftICAO'     => $aircraftinfo->icao,
								'aircraftFullName' => $aircraftinfo->fullname,								
								'aircraftMaxPax'   => $aircraftinfo->maxpax,
								'aircraftCargo'    => $aircraftinfo->maxcargo,								
								'aircraftName'     => $aircraftinfo->name,
								'aircraftRange'    => $aircraftinfo->range,
								'aircraftWeight'   => $aircraftinfo->weight,
								'aircraftCruise'   => $aircraftinfo->cruise
								);	
						
						$send = $this->sendXML($params);
						break;

			}
			
		}
	}
	
	public function ConvertMinutes2Hours($Minutes)
	{
		if ($Minutes < 0)
		{
			$Min = Abs($Minutes);
		}
		else
		{
			$Min = $Minutes;
		}
		$iHours = Floor($Min / 60);
		$Minutes = ($Min - ($iHours * 60)) / 100;
		$tHours = $iHours + $Minutes;
		if ($Minutes < 0)
		{
			$tHours = $tHours * (-1);
		}
		$aHours = explode(".", $tHours);
		$iHours = $aHours[0];
		if (empty($aHours[1]))
		{
			$aHours[1] = "00";
		}
		$Minutes = $aHours[1];
		if (strlen($Minutes) < 2)
		{
			$Minutes = $Minutes ."0";
		}
		$tHours = $iHours .":". $Minutes;
		return $tHours;
	}
	
	public function sendXML($params)
	{
		$xml = new SimpleXMLElement("<sitedata />");
		
		$info_xml = $xml->addChild('info');
		foreach($params as $name => $value)
		{
			$info_xml->addChild($name, $value);
		}
		
		header('Content-type: text/xml'); 		
		$xml_string = $xml->asXML();
		echo $xml_string;
		
		# For debug
		#$this->log("Sending: \n".print_r($xml_string, true), 'kacars');
		
		return;	
	}
	
	public function getAllAircraft()
	{
		$results = OperationsData::getAllAircraft(true);
		
		$xml = new SimpleXMLElement("<aircraftdata />");
		
		$info_xml = $xml->addChild('info');
		
		foreach($results as $row)
		{
			$info_xml->addChild('aircraftICAO', $row->icao);
			$info_xml->addChild('aircraftReg', $row->registration);
		}
		
		# For debug
		#$this->log("Sending: \n".print_r($xml_string, true), 'kacars');
		
		header('Content-type: text/xml');
		echo $xml->asXML();
	}	
}