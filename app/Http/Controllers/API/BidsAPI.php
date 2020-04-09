<?php

namespace App\Http\Controllers\API;

use App\Models\AircraftGroup;
use App\Models\Airline;
use App\Models\Flight;
use App\Models\Schedule;
use App\Models\ScheduleComplete;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BidsAPI extends Controller
{
    public function getBid(Request $request)
    {
        // Ok lets find out if we can find the bid
        $flights = Flight::where('state', 1)->with('depapt')->with('arrapt')->with('airline')->with('aircraft')->get();
        if ($request->query('format') == 'xacars') {
            $user   = User::where('username', $request->query('username'))->first();
            $flight = self::getProperFlightNum($request->query('flightnum'), $user->id);

            return response()->json([
                'status' => 200,
                'bid'    => $flight,
            ]);
        }
        if ($flights === null) {
            // We didn't find shit for that user. Return a 404
            return json_encode([
                'status' => 404,
            ]);
        }
        foreach ($flights as $f) {
            if (is_null($f->callsign)) {
                $f->callsign = $f->airline->icao.$f->flightnum;
            }
        }
        // Ok now lets do a general query
        return response()->json($flights);
    }

    public function fileBid(Request $request)
    {
        $template = Schedule::where('id', $request->input('schedule_id'))->with('depapt')->with('arrapt')->with('airline')->with('aircraft_group')->first();
        //$template = Schedule::where('id', $request->query('schedule_id'))->first();
        // Now let's turn the aircraft group into a assigned aircraft.
        // Let's start by getting the group's assigned aircraft list.
        if ($template->aircraft_group_id != null) {
            $acfgrp = AircraftGroup::where('id', $template->aircraft_group->id)->with('aircraft')->first();
        } else {
            $acfgrp = AircraftGroup::with('aircraft')->first();
        }

        // ok lets assign the first aircraft on the list
        // TODO Change aircraft selection behavior. Current: First on list

        $complete = new ScheduleComplete();

        // First let's bring all the foreign keys from the previous table into this one.

        $complete->airline()->associate($template->airline);
        $complete->depapt()->associate($template->depapt);
        $complete->arrapt()->associate($template->arrapt);
        $complete->aircraft()->associate($acfgrp->aircraft[0]);
        $complete->user()->associate($request->input('userid'));

        // Lets JSON decode the defaults so we can place the route correctly within the system.

        $defaults = json_decode($template->defaults);

        $complete->flightnum = $template->flightnum;
        $complete->route     = $defaults['route'];
        // Now lets encode the cruise altitude in the JSON
        $rte_data = [];

        $rte_data['cruise'] = $defaults['cruise'];
        // store it

        $complete->route_data = json_encode($rte_data);

        $complete->deptime = Carbon::now();
        $complete->arrtime = Carbon::now();
        $complete->load    = 0;
        $complete->save();

        return response()->json([
            'status' => 200,
        ]);
    }

    public function view($id)
    {
        $flight = Flight::with('user')->with('airline')->with('depapt')->with('arrapt')->with('aircraft')->find($id);

        return response()->json($flight);
    }

    private static function getProperFlightNum($flightnum, $userid)
    {
        if ($flightnum == '') {
            return false;
        }

        $ret       = [];
        $flightnum = strtoupper($flightnum);
        $airlines  = Airline::all();

        foreach ($airlines as $a) {
            $a->icao = strtoupper($a->icao);

            if (strpos($flightnum, $a->icao) === false) {
                continue;
            }

            $ret['icao']      = $a->icao;
            $ret['flightnum'] = str_ireplace($a->icao, '', $flightnum);

            // ok now that we deduced that, let's find the bid.
            //dd($userid);
            return Flight::where(['user_id' => $userid, 'airline_id' => $a->id, 'flightnum' => $ret['flightnum']])->with('depapt')->with('arrapt')->with('airline')->with('aircraft')->first();
        }

        // Invalid flight number
        $ret['code']      = '';
        $ret['flightnum'] = $flightnum;

        return Flight::where(['user_id' => $userid, 'flightnum' => $ret['flightnum']])->with('depapt')->with('arrapt')->with('airline')->with('aircraft')->first();
    }
}
