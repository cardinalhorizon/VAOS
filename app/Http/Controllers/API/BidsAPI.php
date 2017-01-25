<?php

namespace App\Http\Controllers\API;

use App\Bid;
use App\ScheduleTemplate;
use App\ScheduleComplete;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\AircraftGroup;
use App\Models\Legacy;
use App\Classes\VAOSHelpers;
use App\Airline;

use Symfony\Component\Routing\Tests\Fixtures\RedirectableUrlMatcher;

class BidsAPI extends Controller
{

    public function getBid(Request $request)
    {
        // Ok lets find out if we can find the bid
        $bids = ScheduleComplete::where('user_id', $request->query('userid'))->with('depapt')->with('arrapt')->with('airline')->with('aircraft')->get();
        if ($request->query('format') == "xacars")
        {
            $user = User::where('username', $request->query('username'))->first();
            $bid = self::getProperFlightNum($request->query('flightnum'), $user->id);

            return response()->json([
                'status' => 200,
                'bid' => $bid
            ]);
        }
        if ($bids === null)
        {
            // We didn't find shit for that user. Return a 404
            return json_encode([
                'status' => 404
            ]);
        }
        // Ok now lets do a general query
        return response()->json([
            'status' => 200,
            'bids' => $bids
        ]);
    }
    public function fileBid(Request $request)
    {
        $template = ScheduleTemplate::where('id', $request->input('schedule_id'))->with('depapt')->with('arrapt')->with('airline')->with('aircraft_group')->first();
        //$template = ScheduleTemplate::where('id', $request->query('schedule_id'))->first();
        // Now let's turn the aircraft group into a assigned aircraft.
        // Let's start by getting the group's assigned aircraft list.
        if ($template->aircraft_group_id != null)
            $acfgrp = AircraftGroup::where('id', $template->aircraft_group->id)->with('aircraft')->first();
        else
            $acfgrp = AircraftGroup::with('aircraft')->first();

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

        if (env('LEGACY_SUPPORT'))
        {
            // Add the schedule template into the legacy table
            $legacy = Legacy\Schedule::firstOrNew(['code' => $template->airline->icao, 'flightnum' => $template->flightnum]);
            $legacy->code = $template->airline->icao;
            $legacy->flightnum = $template->flightnum;
            $legacy->depicao = $template->depapt->icao;
            $legacy->arricao = $template->arrapt->icao;
            if ($template->route = null)
                $legacy->route = $template->route;
            else
                $legacy->route = "NO ROUTE";
            $legacy->aircraft = $acfgrp->aircraft[0]->id;
            $legacy->distance = VAOSHelpers::getDistance($template->depapt->lat, $template->depapt->lon, $template->arrapt->lat, $template->arrapt->lon, "M");
            $legacy->deptime = Carbon::now()->toTimeString();
            $legacy->arrtime = Carbon::now()->addHours(2)->toTimeString();
            $legacy->flighttime = "0";
            $legacy->notes = "VAOS GENERATED ROUTE";
            $legacy->route_details = "{[]}";
            $legacy->flightlevel = "35000";
            $legacy->enabled = 1;
            $legacy->price = 175;
            $legacy->flighttype = "P";
            $legacy->daysofweek = "0123456";
            $legacy->save();

            // Now let's add the bid appropriately

            $legacybid = new Legacy\Bid();
            $legacybid->pilotid = $request->input('userid');
            $legacybid->routeid = $legacy->id;
            $legacybid->dateadded = Carbon::now();
            $legacybid->save();

            $legacy->bidid = $legacybid->id;
            $legacy->save();
        }

        return response()->json([
            'status' => 200
        ]);
    }
    private static function getProperFlightNum($flightnum, $userid) {
        if ($flightnum == '') return false;

        $ret = array();
        $flightnum = strtoupper($flightnum);
        $airlines = Airline::all();

        foreach ($airlines as $a) {
            $a->icao = strtoupper($a->icao);

            if (strpos($flightnum, $a->icao) === false) {
                continue;
            }

            $ret['icao'] = $a->icao;
            $ret['flightnum'] = str_ireplace($a->icao, '', $flightnum);

            // ok now that we deduced that, let's find the bid.
            //dd($userid);
            return Bid::where(['user_id' => $userid, 'airline_id' => $a->id, 'flightnum' => $ret['flightnum']])->with('depapt')->with('arrapt')->with('airline')->with('aircraft')->first();
        }

        # Invalid flight number
        $ret['code'] = '';
        $ret['flightnum'] = $flightnum;
        return Bid::where(['user_id' => $userid, 'flightnum' => $ret['flightnum']])->with('depapt')->with('arrapt')->with('airline')->with('aircraft')->first();
    }
}
