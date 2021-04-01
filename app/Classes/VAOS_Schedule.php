<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 10/23/16
 * Time: 8:55 PM.
 */

namespace App\Classes;

use App\Models\AviationGroup;
use App\Models\Airport;
use App\Models\Schedule;
use App\Models\AircraftGroup;
use App\Models\Flight as ScheduleComplete;

class VAOS_Schedule
{
    private static function assignAircraft($aircraftGroup = null)
    {
        foreach ($aircraftGroup as $a) {
            if ($a['pivot']['primary']) {
                $acfgrp = AircraftGroup::where('id', $a->id)->with('aircraft')->first();

                // ok, run an availability check.
                foreach ($acfgrp->aircraft as $acf) {
                    if ($acf->isAvailable()) {
                        return $acf;
                    }
                }
            }
        }
    }
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
        $assigned = false;
        if ($template->aircraft_group != null) {
            foreach ($template->aircraft_group as $a) {
                if ($a['pivot']['primary']) {
                    $acfgrp = AircraftGroup::where('id', $a->id)->with('aircraft')->first();
                    // ok, run an availability check.
                    foreach ($acfgrp->aircraft as $acf) {
                        if ($acf->isAvailable()) {
                            $complete->aircraft()->associate($acf);
                            $assigned = true;
                            break;
                        }
                    }
                }
                if ($assigned)
                {
                    break;
                }
            }
        }
        if (!$assigned)
        {
            throwException(new \Exception("No Available Aircraft in Assigned Aircraft Groups", 500));
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

        $complete->user()->associate($user_id);

        // Lets JSON decode the defaults so we can place the route correctly within the system.

        $defaults = json_decode($template->defaults);
        $complete->flightnum = $template->flightnum;
        //$complete->route     = $defaults['route'];
        // Now lets encode the cruise altitude in the JSON
        $rte_data = [];

        //$rte_data['cruise'] = $defaults['cruise'];
        // store it

        $complete->route_data = json_encode($rte_data);
        if ($template->callsign === null)
        {
            $complete->callsign = $template->airline->icao.$template->flightnum;
        }
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
        //dd($data);
        // Before we add the route, lets check to see if the airport exists.
        $dep = VAOS_Airports::checkOrAdd($data->depapt);
        $arr = VAOS_Airports::checkOrAdd($data->arrapt);
        // add the form elements
        // Search for the airline in the database

        $entry->flightnum = $data->flightnum;

        // Setup the foreign keys. Lets now find the new airports

        $entry->depapt()->associate($dep);
        $entry->arrapt()->associate($arr);
        $entry->airline()->associate($data->airline->id);

        //dd($data);

        $entry->seasonal = false;
        //$entry->daysofweek = "0123456";
        $entry->type = 1;
        if (array_key_exists('enabled', $data)) {
            $entry->enabled = $data['enabled'];
        } else {
            $entry->enabled = 1;
        }
        try {
            $entry->save();
        }
        catch (\Exception $e)
        {
            dd([
                $data,
                $dep,
                $arr,
                $entry
            ]);
        }

        $entry->aircraft_group()->attach($data->primary_group->id, ['primary' => true]);
        // aircraft group assignment
        foreach ($data->aircraft_groups as $group) {
            //dd($group);
            // $acfgrp = AircraftGroup::where('icao', ($data['aircraft_group']))->first();
            $entry->aircraft_group()->attach($group->id, ['primary' => false]);
        }
        try {
            $entry->save();
        }
        catch (\Exception $e)
        {
            dd([
                $data,
                $dep,
                $arr,
                $entry
            ]);
        }
    }

    public static function updateRoute($obj, $id)
    {
        // Declare a new instance of the Schedule Model
        $entry = Schedule::find($id);
        $data = $obj['route_info'];
        //dd($request);

        $entry->flightnum = $data['flightnum'];

        if (array_key_exists('alticao', $data)) {
            $entry->alticao = $data['alticao'];
        }
        if (array_key_exists('route', $data)) {
            $entry->route = $data['route'];
        }
        //dd($data);
        // clear all aircraft groups and update them.
        $entry->aircraft_group()->detach();

        $entry->aircraft_group()->attach($data['primary_group']['id'], ['primary' => true]);
        // aircraft group assignment

        $entry->seasonal = false;
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
