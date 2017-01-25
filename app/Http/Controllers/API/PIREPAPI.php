<?php

namespace App\Http\Controllers\API;

use App\Classes\phpVMSLegacy;
use App\PIREP;
use App\PIREPComment;
use App\User;
use App\Airline;
use App\Bid;
use Illuminate\Http\Request;

use App\Http\Requests;

class PIREPAPI extends Controller
{
    /**
     * File a PIREP into the system.
     * @param Request $request
     */
    public function filePIREP(Request $request)
    {
        // first lets check to see if we have everything required for the request
        $input = $request->all();
        $data = array();

        // First lets update financial data.

        // TODO: Make Financial Data Calls and Updates

        // TODO: Check Auto Accept PIREP Values

        // TODO: Add PIREP To Database
        $pirep = new PIREP();

        // first let's retrieve the original bid from the database and enter in all the values


        if ($request->query('format') == 'phpVMS')
        {
            $pirep->user()->associate($request->input('pilotid'));
            // This is a legacy ACARS client. Treat it with respect, they won't be around
            // for too much longer. All we need is the user data, flight info and we are all set
            //dd($request->all());
            $flightinfo = self::getProperFlightNum($request->input('flightnum'), $request->input('pilotid'));
            //dd($flightinfo);
            $pirep->airline()->associate($flightinfo->airline_id);
            $pirep->aircraft()->associate($flightinfo->aircraft_id);
            $pirep->depapt()->associate($flightinfo->depapt_id);
            $pirep->arrapt()->associate($flightinfo->arrapt_id);
            $pirep->flightnum = $flightinfo->flightnum;
            $pirep->route = "NOT SUPPORTED";
            $pirep->status = 0;
            $pirep->landingrate = $request->input('landingrate');

            // Auto Accept System
            if (env('VAOS_AA_ENABLED')) {
                if ($request->input('landingrate') >= env('VAOS_AA_LR'))
                    $pirep->status = 1;
            }
            if (env('VAOS_AA_ALL'))
                $pirep->status = 1;

            $pirep->save();
            // now let's take care of comments.
            $comment = new PIREPComment();

            $comment->user()->associate($request->input('pilotid'));
            $comment->pirep()->associate($pirep);
            $comment->comment = $request->input('comment');
            $comment->save();

            // Time to delete the bid from the table.

            $flightinfo->delete();

            return response()->json([
                'status' => 200
            ]);
        }
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
