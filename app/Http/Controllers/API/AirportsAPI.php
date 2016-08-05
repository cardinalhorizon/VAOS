<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use \Illuminate\Support\Collection;
use App\Models\Airport;
use App\Http\Requests;
use GuzzleHttp\Client;

class AirportsAPI extends Controller
{
    //
    public function index()
    {
    	// return everything
    	return Airport::all();
    }
    public function add(Request $request)
    {
    	// lets request the airport identifier from the central database
    	$client = new Client();
    	$res = $client->request('GET', 'http://fsvaos.net/api/central/airports', [
    		'query' => [
    			'icao' => $request->icao,
    			]
    		])->getBody();
    	// Add the airport to the database
    	$data = json_decode($res, true);
    	$airport = new Airport();
    	//return dd($data);
    	$airport->id = $data[0]['id'];
    	$airport->name = $data[0]['name'];
    	$airport->icao = $data[0]['ident'];
    	$airport->lat = $data[0]['latitude_deg'];
    	$airport->lon = $data[0]['longitude_deg'];
    	$airport->country = $data[0]['iso_country'];
    	
    	$airport->save();
    	$ret = "Added ".$data[0]['name']. "to the database";
    	return $ret;
    }
    public function addFromDB($icao)
    {
    	// lets request the airport identifier from the central database
    	$client = new Client();
    	$res = $client->request('GET', 'http://fsvaos.net/api/central/airports', [
    		'query' => [
    			'icao' => $icao,
    			]
    		])->getBody();
    	// Add the airport to the database
    	$data = json_decode($res, true);
    	$airport = new Airport();
    	//return dd($data);
    	try {
    	$airport->id = $data[0]['id'];
    	$airport->name = $data[0]['name'];
    	$airport->city = $data[0]['municipality'];
    	$airport->iata = $data[0]['iata_code'];
    	$airport->icao = $data[0]['ident'];
    	$airport->lat = $data[0]['latitude_deg'];
    	$airport->lon = $data[0]['longitude_deg'];
    	$airport->country = $data[0]['iso_country'];
    	}
    	catch (Exception $e)
    	{ 
    		return dd($data); 
    		
    	}
    	$airport->save();
    }
}
