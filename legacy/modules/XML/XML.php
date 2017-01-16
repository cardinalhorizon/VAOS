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
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
 
class XML extends CodonModule
{
	#
	# Get all of the current acars flight
	#	Output in XML
	#
	public function acarsdata()
	{
		$output = '';
		
		CodonEvent::Dispatch('refresh_acars', 'XML');
		
		$flights = ACARSData::GetACARSData();
		
		$xml = new SimpleXMLElement('<livemap/>');
		
		if(!$flights) $flights = array();		
		foreach($flights as $flight)
		{	
			$pilot = $xml->addChild('aircraft');
			
			$pilot->addAttribute('flightnum', $flight->flightnum);
			$pilot->addAttribute('lat', $flight->lat);
			$pilot->addAttribute('lng', $flight->lng);
			
			$pilot->addChild('pilotid', PilotData::GetPilotCode($flight->code, $flight->pilotid));
			$pilot->addChild('pilotname', $flight->firstname.' '.$flight->lastname);
			$pilot->addChild('flightnum', $flight->flightnum);
			$pilot->addChild('lat', $flight->lat);
			$pilot->addChild('lng', $flight->lng);
			$pilot->addChild('depicao', $flight->depicao);
			$pilot->addChild('arricao', $flight->arricao);
			$pilot->addChild('phase', $flight->phasedetail);
			$pilot->addChild('alt', $flight->alt);
			$pilot->addChild('gs', $flight->gs);
			$pilot->addChild('distremain', $flight->distremain);
			$pilot->addChild('timeremain', $flight->timeremain);
		}
		
		header('Content-type: text/xml'); 
		echo $xml->asXML();
	}
	
	public function version()
	{
		$xml = new SimpleXMLElement('<sitedata />');
		$xml->addChild('version', PHPVMS_VERSION);
		
		header('Content-type: text/xml');
		echo $xml->asXML();
	}
	
	public function getairlines()
	{
		$xml = new SimpleXMLElement("<sitedata />");
		
		$airlines = OperationsData::GetAllAirlines();
		
		foreach($airlines as $a)
		{
			$airline_xml = $xml->addChild('airline');
			$airline_xml->addChild('code', $a->code);
			$airline_xml->addChild('name', $a->name);
			$airline_xml->addChild('enabled', $a->enabled);
		}
		
		header('Content-type: text/xml'); 		
		echo $xml->asXML();
	}
	
	#
	# Get XML-ized output for the flight plan (dept/arr)
	#
	public function flightinfo($route = '')
	{
		if($route == '')
			$route = $_GET['route'];
		
		preg_match('/^([A-Za-z]{2,3})(\d*)/', $route, $matches);
		$code = $matches[1];
		$flightnum = $matches[2];
		
		$params = array('s.code' => $code, 's.flightnum' => $flightnum);
		$flightinfo = SchedulesData::findSchedules($params, 1);
				
		if(!$flightinfo)
			return;
			
		$flightinfo = $flightinfo[0]; // Grab the first one
		$xml = new SimpleXMLElement('<flightinfo/>');
		
		$dep = $xml->addChild('departure');
		$dep->addAttribute('icao', $flightinfo->depicao);
		$dep->addAttribute('name', $flightinfo->depname);
		$dep->addAttribute('lat', $flightinfo->deplat);
		$dep->addAttribute('lng', $flightinfo->deplng);
		
		$arr = $xml->addChild('arrival');
		$arr->addAttribute('icao', $flightinfo->arricao);
		$arr->addAttribute('name', $flightinfo->arrname);
		$arr->addAttribute('lat', $flightinfo->arrlat);
		$arr->addAttribute('lng', $flightinfo->arrlng);
		
		header('Content-type: text/xml');
		echo $xml->asXML();	
	}
	
	public function routeinfo($depicao = '', $arricao = '')
	{
		header('Content-type: text/xml');
		
		if($depicao == '')
			$depicao = $_GET['depicao'];
		
		if($arricao == '')
			$arricao = $_GET['arricao'];
		
		if($depicao == '' || $arricao == '')
			return;
		
		$depinfo = OperationsData::GetAirportInfo($depicao);
		if(!$depinfo)
		{
			$depinfo = OperationsData::RetrieveAirportInfo($depicao);
		}
		
		$arrinfo = OperationsData::GetAirportInfo($arricao);
		if(!$arrinfo)
		{
			$arrinfo = OperationsData::RetrieveAirportInfo($arricao);
		}
		
		
		$xml = new SimpleXMLElement('<flightinfo/>');
		
		$dep = $xml->addChild('departure');
		$dep->addAttribute('icao', $depinfo->icao);
		$dep->addAttribute('name', $depinfo->name);
		$dep->addAttribute('country', $depinfo->country);
		$dep->addAttribute('lat', $depinfo->lat);
		$dep->addAttribute('lng', $depinfo->lng);
		
		$arr = $xml->addChild('arrival');
		$arr->addAttribute('icao', $arrinfo->icao);
		$arr->addAttribute('name', $arrinfo->name);
		$arr->addAttribute('country', $arrinfo->country);
		$arr->addAttribute('lat', $arrinfo->lat);
		$arr->addAttribute('lng', $arrinfo->lng);
		
		header('Content-type: text/xml');
		echo $xml->asXML();	
	}
}