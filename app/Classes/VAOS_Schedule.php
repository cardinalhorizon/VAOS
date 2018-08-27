<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 10/23/16
 * Time: 8:55 PM.
 */

namespace App\Classes;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Schedule;
use App\Models\AircraftGroup;
use App\Models\Flight as ScheduleComplete;

class VAOS_Schedule
{
    public static function fileBid($user_id, $schedule_id, $aircraft_id = null)
    {
        $complete = new ScheduleComplete();
        $template = Schedule::where('id', $schedule_id)->with('depapt')->with('arrapt')->with('airline')->with('aircraft_group')->first();
        // $template = Schedule::where('id', $request->query('schedule_id'))->first();
        // Now let's turn the aircraft group into a assigned aircraft.
        /*
         * New in 2.0 We now have multiple aircraft groups and aircraft assigned to a single object. Let's do ourselves
         * a favor and let's get priorities straight.
         *
         * Priority 1 is individual aircraft assignment, Priority 2 is aircraft groups.
         *
         * Aircraft will first be checked to see if they are available. This will be done via the Model directly.
         */

        if ($template->aircraft_group != null) {
            foreach ($template->aircraft_group as $a) {
                if ($a['pivot']['primary']) {
                    $acfgrp = AircraftGroup::where('id', $a->id)->with('aircraft')->first();

                    // ok, run an availability check.
                    foreach ($acfgrp->aircraft as $acf) {
                        if ($acf->isAvailable()) {
                            $complete->aircraft()->associate($acf);
                        }
                    }
                }
            }
        }
        //$acfgrp = AircraftGroup::where('id', $template->aircraft_group->pivot->primary)->with('aircraft')->first();
        else {
            $acfgrp = AircraftGroup::with('aircraft')->first();
        }

        // ok lets assign the first aircraft on the list

        // First let's bring all the foreign keys from the previous table into this one.

        $complete->airline()->associate($template->airline);
        $complete->depapt()->associate($template->depapt);
        $complete->arrapt()->associate($template->arrapt);
        //dd($acfgrp);
        if ($aircraft_id === null) {
            $complete->aircraft()->associate($acfgrp->aircraft[0]);
        } else {
            $complete->aircraft()->associate($aircraft_id);
        }

        $complete->user()->associate($user_id);

        // Lets JSON decode the defaults so we can place the route correctly within the system.

        $defaults = json_decode($template->defaults);

        $complete->flightnum = $template->flightnum;
        $complete->route     = $defaults['route'];
        // Now lets encode the cruise altitude in the JSON
        $rte_data = [];

        $rte_data['cruise'] = $defaults['cruise'];
        // store it

        $complete->route_data = json_encode($rte_data);

        $complete->deptime = null;
        $complete->arrtime = null;
        $complete->load    = 0;
        $complete->state   = 0;
        $complete->save();

        return true;
    }

    public static function newRoute($data)
    {
        // Declare a new instance of the Schedule Model
        $entry = new Schedule();
        //dd($request);
        // Before we add the route, lets check to see if the airport exists.
        if (Airport::where('icao', $data['depicao'])->first() === null) {
            VAOS_Airports::AddAirport($data['depicao']);
        }
        if (Airport::where('icao', $data['arricao'])->first() === null) {
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

        if (array_key_exists('alticao', $data)) {
            $entry->alticao = $data['alticao'];
        }
        if (array_key_exists('route', $data)) {
            $entry->route = $data['route'];
        }
        //dd($data);

        $entry->seasonal = false;
        //$entry->daysofweek = "0123456";
        $entry->type = $data['type'];
        if (array_key_exists('enabled', $data)) {
            $entry->enabled = $data['enabled'];
        } else {
            $entry->enabled = 1;
        }
        $entry->save();
        if (array_key_exists('aircraft_group', $data)) {
            //dd($data);
            // $acfgrp = AircraftGroup::where('icao', ($data['aircraft_group']))->first();
            $entry->aircraft_group()->attach($data['aircraft_group'], ['primary' => true]);
        }
        $entry->save();
    }

    public static function updateRoute($data, $id)
    {
        // Declare a new instance of the Schedule Model
        $entry = Schedule::find($id);
        //dd($request);
        // Before we add the route, lets check to see if the airport exists.
        if (Airport::where('icao', $data['depicao'])->first() === null) {
            VAOS_Airports::AddAirport($data['depicao']);
        }
        if (Airport::where('icao', $data['arricao'])->first() === null) {
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

        if (array_key_exists('alticao', $data)) {
            $entry->alticao = $data['alticao'];
        }
        if (array_key_exists('route', $data)) {
            $entry->route = $data['route'];
        }
        //dd($data);
        /*
        if (array_key_exists('aircraft_group', $data)) {
            //dd($data);
            $acfgrp = $acfgrp = AircraftGroup::where('icao', ($data['aircraft_group']))->first();
            $entry->aircraft_group()->attach($data['aircraft_group'], ['primary' => true]);
        }*/
        $entry->seasonal = true;
        //$entry->daysofweek = "0123456";
        $entry->type = $data['type'];
        if (array_key_exists('enabled', $data)) {
            $entry->enabled = $data['enabled'];
        } else {
            $entry->enabled = 1;
        }
        $entry->save();
    }

    public static function deleteBid($flight_id, $user_id = null)
    {
        if (is_null($user_id)) {
            $flight = ScheduleComplete::find($flight_id);
        } else {
            $flight = ScheduleComplete::where(['user_id' => $user_id, 'id' => $flight_id])->firstOrFail();
        }

        $flight->delete();
    }
}
