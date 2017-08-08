<?php

namespace App\Http\Controllers\API;
use App\AircraftGroup;
use App\Airline;
use App\Classes\VAOS_Airports;
use App\Classes\VAOS_Schedule;
use App\Models\Airport;
use App\Http\Controllers;
use App\ScheduleTemplate;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Requests;

class ScheduleAPI extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index(Request $request)
    {
        if ($request->input('codeshare') == true)
        {

        }
        else {

            $schedule = ScheduleTemplate::with('depapt')->with('arrapt')->with('airline')->with('aircraft_group')->get();
            return response()->json([
                'status' => 200,
                'schedule' => $schedule
            ]);
        }
    }

    /**
     * @param Request $request
     */
    public function get(Request $request)
    {
        // find out if this is calling the ID or the airport identifier
        if ($request->has('depicao'))
        {
            $route = ScheduleTemplate::where('depicao', $request->depicao)->get();
        }
        if ($request->has('arricao'))
        {
        	$route = ScheduleTemplate::where('arricao', $request->arricao)->get();
        }
        if ($request->has('id'))
        {
            $route = ScheduleTemplate::find($request->id);
        }
        return json_encode(['status' => 200,
            'schedule' => $route]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
    	// Declare a new instance of the Schedule Model
    	$entry = new ScheduleTemplate();
    	
    	// Before we add the route, lets check to see if the airport exists.
    	if (Airport::where('icao', $request->input('depicao'))->first() === null)
    	{
    		VAOS_Airports::AddAirport($request->input('depicao'));
    	}
    	if (Airport::where('icao', $request->input('arricao'))->first() === null)
    	{
            VAOS_Airports::AddAirport($request->input('arricao'));
    	}
    	// add the form elements
        // Search for the airline in the database


    	$entry->flightnum = $request->input('flightnum');

        // Setup the foreign keys. Lets now find the new airports

        $dep = Airport::where('icao', $request->input('depicao'))->first();
        $arr = Airport::where('icao', $request->input('arricao'))->first();
    	$entry->depapt()->associate($dep);
    	$entry->arrapt()->associate($arr);
        $airline = Airline::where('icao', $request->input('code'))->first();
        $entry->airline()->associate($airline);

    	if ($request->has('alticao'))
    	{
    		$entry->alticao = $request->input('alticao');
    	}
    	if ($request->has('route'))
    	{
    		$entry->route = $request->input('route');
    	}
    	if ($request->has('aircraft_group'))
    	{
    	    $acfgrp = AircraftGroup::where('icao', $request->input('aircraft_group'))->first();
    		$entry->aircraft_group()->associate($acfgrp);
    	}
    	$entry->seasonal = true;
    	//$entry->daysofweek = "0123456";
    	$entry->type = $request->input('type');
    	if ($request->has('enabled'))
    	{
    		$entry->enabled = $request->input('enabled');
    	}
    	else
    	{
    		$entry->enabled = 1;
    	}
    	$entry->save();


    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }
    public function jsonadd(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        foreach ($data as $d)
        {
            VAOS_Schedule::newRoute([
                'depicao' => $d['depicao'],
                'arricao' => $d['arricao'],
                'airline' => $d['airline'],
                'flightnum' => $d['flightnum'],
                'aircraft_group' => $d['aircraft_group'],
                'enabled' => $d['enabled']
            ]);
        }
        return response()->json([
            'status' => 200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
    }
    public function test(Request $request)
    {

    }
}
