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
    	$airport->id = $data['airport']['id'];
    	$airport->name = $data['airport']['name'];
    	$airport->icao = $data['airport']['ident'];
    	$airport->lat = $data['airport']['latitude_deg'];
    	$airport->lon = $data['airport']['longitude_deg'];
    	$airport->country = $data['airport']['iso_country'];
    	
    	$airport->save();
    	$ret = "Added ".$data[0]['name']. "to the database";
    	return $ret;
    }
    public static function addFromDB($icao)
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
            $airport->id = $data['airport']['id'];
            $airport->name = $data['airport']['name'];
            $airport->icao = $data['airport']['ident'];
            $airport->iata = $data['airport']['iata_code'];
            $airport->lat = $data['airport']['latitude_deg'];
            $airport->lon = $data['airport']['longitude_deg'];
            $airport->city = $data['airport']['municipality'];
            $airport->country = $data['airport']['iso_country'];
    	}
    	catch (Exception $e)
    	{ 
    		return dd($data);
    		
    	}
    	$airport->save();
    }
    public function addHub(Request $request)
    {
        // lets add the hub of an existing airport into the database.
    }
}
