<?php
/* Copyright (C) 2016 Cardinal Horizon Studios - All Rights Reserved
 * You may use, distribute and modify this code under the
 * terms of the XYZ license, which unfortunately won't be
 * written for another century.
 *
 * You should have received a copy of the XYZ license with
 * this file. If not, please write to: , or visit :
 */
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Aircraft;
use App\Models\SeatLayout;
use App\Http\Requests;

class FleetAPI extends Controller
{
    public function index()
    {
    	return Aircraft::all();
    }
    public function add(Request $request)
    {
    	// Ask if we are supplying our own data or requesting it from the server
    	if ($request->has('remote') && $request->remote = 'true')
    	{
    		$client = new Client();
    		$res = $client->request('GET', 'http://fsvaos.net/api/central/aircraft', [
    			'query' => [
    				'icao' => $request->icao,
    				]
    			])->getBody();
    		// Add the airport to the database
    		$data = json_decode($res, true);
    		$airport = new Aircraft();
    		//return dd($data);
    		$airport->name = $data[0]['name'];
    		$airport->icao = $data[0]['icao'];
    		$airport->maxgw = $data[0]['maxgw'];
    		$airport->maxpax = $data[0]['maxpax'];
    		$airport->range = $data[0]['range'];
    		$airport->registration = $request->input('registration');
    		$airport->enabled = true;
    		$airport->hub = $request->input('hub');
    		$airport->save();
    	}
    	// use the rest of the query in the URI to add it to the system.
    	else
    	{
    		// insert manual entry system.
    	}
    }
    public function addseats(Request $request)
    {
    	
    }
}
