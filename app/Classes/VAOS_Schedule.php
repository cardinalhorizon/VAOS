<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 10/23/16
 * Time: 8:55 PM
 */

namespace App\Classes;


use App\AircraftGroup;
use App\Models\Airport;
use App\Airline;
use App\ScheduleComplete;
use App\ScheduleTemplate;
use Carbon\Carbon;

class VAOS_Schedule
{
    public static function fileBid($user_id, $schedule_id, $aircraft_id = null)
    {
        $complete = new ScheduleComplete();
        $template = ScheduleTemplate::where('id', $schedule_id)->with('depapt')->with('arrapt')->with('airline')->with('aircraft_group')->first();
        //$template = ScheduleTemplate::where('id', $request->query('schedule_id'))->first();
        // Now let's turn the aircraft group into a assigned aircraft.
        // Let's start by getting the group's assigned aircraft list.
        if ($template->aircraft_group_id != null)
        {
            $acfgrp = AircraftGroup::where('id', $template->aircraft_group->id)->with('aircraft', 'aircraft.airline')->first();
            $airline_aircraft = [];
            foreach($acfgrp->aircraft as $a)
            {
                if ($a->airline->id === $template->airline_id)
                {
                    $airline_aircraft[] = $a;
                }
            }
            
            $complete->aircraft()->associate($airline_aircraft[0]);
        }
        else
        {

            if (is_null($aircraft_id))
            {
                throw new Exception('Aircraft is null. Aircraft group is not assigned.');
            }
            else {
                $complete->aircraft()->associate($aircraft_id);
            }
        }
            

        

        // First let's bring all the foreign keys from the previous table into this one.

        $complete->airline()->associate($template->airline);
        $complete->depapt()->associate($template->depapt);
        $complete->arrapt()->associate($template->arrapt);
        //dd($acfgrp);
        
        $complete->user()->associate($user_id);

        // Lets JSON decode the defaults so we can place the route correctly within the system.

        $defaults = json_decode($template->defaults);

        $complete->flightnum = $template->flightnum;
        $complete->route = $defaults['route'];
        // Now lets encode the cruise altitude in the JSON
        $rte_data = array();

        $rte_data['cruise'] = $defaults['cruise'];
        // store it

        $complete->route_data = json_encode($rte_data);

        $complete->deptime = Carbon::now();
        $complete->arrtime = Carbon::now();
        $complete->load = 0;
        $complete->save();

        return true;
    }
    public static function newRoute($data)
    {
        // Declare a new instance of the Schedule Model
        $entry = new ScheduleTemplate();
        //dd($request);
        // Before we add the route, lets check to see if the airport exists.
        if (Airport::where('icao', $data['depicao'])->first() === null)
        {
            VAOS_Airports::AddAirport($data['depicao']);
        }
        if (Airport::where('icao', $data['arricao'])->first() === null)
        {
            VAOS_Airports::AddAirport($data['arricao']);
        }
        // add the form elements
        // Search for the airline in the database


        $entry->flightnum = $data['flightnum'];

        // Setup the foreign keys. Lets now find the new airports

        $dep = Airport::where('icao', $data['depicao'])->first();
        $arr = Airport::where('icao', $data['arricao'])->first();
        $entry->depapt()->associate($dep);
        $entry->arrapt()->associate($arr);
        $airline = Airline::where('icao', $data['airline'])->first();
        $entry->airline()->associate($airline);

        if (array_key_exists('alticao', $data))
        {
            $entry->alticao = $data['alticao'];
        }
        if (array_key_exists('route', $data))
        {
            $entry->route = $data['route'];
        }
        //dd($data);
        if (array_key_exists('aircraft_group', $data))
        {
            //dd($data);
            $acfgrp = AircraftGroup::where('icao', ($data['aircraft_group']))->first();
            $entry->aircraft_group()->associate($acfgrp);
        }
        $entry->seasonal = false;
        //$entry->daysofweek = "0123456";
        $entry->type = $data['type'];
        if (array_key_exists('enabled', $data))
        {
            $entry->enabled = $data['enabled'];
        }
        else
        {
            $entry->enabled = 1;
        }
        $entry->save();
    }
    public static function updateRoute($data, $id)
    {
        // Declare a new instance of the Schedule Model
        $entry = ScheduleTemplate::find($id);
        //dd($request);
        // Before we add the route, lets check to see if the airport exists.
        if (Airport::where('icao', $data['depicao'])->first() === null)
        {
            VAOS_Airports::AddAirport($data['depicao']);
        }
        if (Airport::where('icao', $data['arricao'])->first() === null)
        {
            VAOS_Airports::AddAirport($data['arricao']);
        }
        // add the form elements
        // Search for the airline in the database


        $entry->flightnum = $data['flightnum'];

        // Setup the foreign keys. Lets now find the new airports

        $dep = Airport::where('icao', $data['depicao'])->first();
        $arr = Airport::where('icao', $data['arricao'])->first();
        $entry->depapt()->associate($dep);
        $entry->arrapt()->associate($arr);
        $airline = Airline::where('icao', $data['airline'])->first();
        $entry->airline()->associate($airline);

        if (array_key_exists('alticao', $data))
        {
            $entry->alticao = $data['alticao'];
        }
        if (array_key_exists('route', $data))
        {
            $entry->route = $data['route'];
        }
        //dd($data);
        if (array_key_exists('aircraft_group', $data))
        {
            //dd($data);
            $acfgrp = $acfgrp = AircraftGroup::where('icao', ($data['aircraft_group']))->first();
            $entry->aircraft_group()->associate($acfgrp);
        }
        $entry->seasonal = true;
        //$entry->daysofweek = "0123456";
        $entry->type = $data['type'];
        if (array_key_exists('enabled', $data))
        {
            $entry->enabled = $data['enabled'];
        }
        else
        {
            $entry->enabled = 1;
        }
        $entry->save();
    }
    public static function deleteBid($bid_id, $user_id = null)
    {
        if (is_null($user_id))
            $bid = ScheduleComplete::find($bid_id);
        else
            $bid = ScheduleComplete::where(['user_id' => $user_id, 'id' => $bid_id])->firstOrFail();

        $bid->delete();
    }
}
