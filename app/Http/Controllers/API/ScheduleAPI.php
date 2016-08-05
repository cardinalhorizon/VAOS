<?php

namespace App\Http\Controllers\API;
use App\Models\Airport;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\API\AirportsAPI;
use App\Http\Requests;

class ScheduleAPI extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Schedule::all();
    }

    /**
     * @param Request $request
     */
    public function get(Request $request)
    {
        // find out if this is calling the ID or the airport identifier
        if ($request->has('depicao'))
        {
            $route = Schedule::where('depicao', $request->depicao)->get();
        }
        if ($request->has('arricao'))
        {
        	$route = Schedule::where('arricao', $request->arricao)->get();
        }
        if ($request->has('id'))
        {
            $route = Schedule::find($request->id);
        }
        return $route;
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
    	$entry = new Schedule();
    	
    	// Before we add the route, lets check to see if the airport exists.
    	if (!Airport::findorfail($request->input('depicao')))
    	{
    		AirportsAPI::addFromDB($request->input('depicao'));
    	}
    	if (!Airport::findorfail($request->input('arricao')))
    	{
    		AirportsAPI::addFromDB($request->input('arricao'));
    	}
    	// add the form elements
    	$entry->code = $request->input('code');
    	$entry->flightnum = $request->input('flightnum');
    	$entry->depicao = $request->input('depicao');
    	$entry->arricao = $request->input('arricao');
    	if ($request->has('alticao'))
    	{
    		$entry->alticao = $request->input('alticao');
    	}
    	if ($request->has('route'))
    	{
    		$entry->route = $request->input('route');
    	}
    	if ($request->has('aircraft'))
    	{
    		$entry->alticao = $request->input('aircraft');
    	}
    	$entry->daysofweek = "0123456";
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
    
    public function jsonadd(Request $request)
    {
    	$data = json_decode($request->getContent(), true);
    	foreach ($data as $d)
    	{
    		$apt = new AirportsAPI();
    		$depicao = Airport::where('icao', $d['depicao'])->get();
    		$arricao = Airport::where('icao', $d['arricao'])->get();
    		if ($depicao->isEmpty())
    		{
    			$apt->addFromDB($d['depicao']);
    		}
    		if ($arricao->isEmpty())
    		{
    			$apt->addFromDB($d['arricao']);
    		}
    		
    		$entry = new Schedule();
    		
    		$entry->code = $d['code'];
    		$entry->flightnum = $d['flightnum'];
    		$entry->depicao = $d['depicao'];
    		$entry->arricao = $d['arricao'];
    		$entry->route = $d['route'];
    		$entry->aircraft = $d['aircraft'];
    		$entry->type = $d['type'];
    		$entry->daysofweek = $d['daysofweek'];
    		$entry->enabled = $d['enabled'];
    		
    		$entry->save();
    	}
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
        //
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
}
