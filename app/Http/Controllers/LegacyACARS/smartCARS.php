<?php

namespace App\Http\Controllers\LegacyACARS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Airline;
use App\User;
use App\Bid;
use App\PIREP;
use App\ACARSData;
use App\PIREPComment;
use App\Models\Legacy;


class smartCARS extends Controller
{
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

        // first let's retrieve the original bid from the database and enter in all the values\

        $pirep->user()->associate($request->input('pilotid'));
        // This is a legacy ACARS client. Treat it with respect, they won't be around
        // for too much longer. All we need is the user data, flight info and we are all set
        $flightinfo = self::getProperFlightNum($request->input('flightnum'), $request->input('pilotid'));
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
    public function positionreport(Request $request)
    {
        $report = array();
        // First off, lets establish the format. Is this phpvms?
        if ($request->query('format') == 'phpVMS')
        {
            // well shoot, we got a legacy ACARS client. Let's sterilize the data and format the input.
            $report['user'] = User::find($request->input('pilotid'));
            $report['user_id'] = $request->input('pilotid');
            // split the flight string the phpVMS way into Airline Code and Flight Number.
            // Why they did this is beyond me. Foreign keys are another story.....
            // phpVMS is a pretty little princess
            $report['bid'] = self::getProperFlightNum($request->input('flightnum'), $request->input('pilotid'));
            //dd($report['bid']);
            // phpVMS sends the aircraft ID from database. Let's use it to our advantage.
            //$report['aircraft'] = Aircraft::where('registration', $request->input('registration'))->first();
            $report['lat'] = $request->input('lat');
            // Lets convert lng to lon. Play with the big boys now
            $report['lon'] = $request->input('lng');
            $report['heading'] = $request->input('heading');
            $report['altitude'] = $request->input('alt');
            $report['groundspeed'] = $request->input('gs');
            /*
            'deptime'
            'arrtime'
            'route'
            'distremain'
            'timeremaining'
            'phasedetail'
            */
            $report['phase'] = $request->input('phasedetail');
            $report['client'] = $request->input('client');
        }
        else
        {
            return response()->json([
                'status' => 800,
            ]);
        }
        // find if the row exists
        $rpt = ACARSData::firstOrNew(['bid_id' => $report['bid']]);
        $rpt->user()->associate($report['user']);
        $rpt->bid()->associate($report['bid']);
        $rpt->lat = $report['lat'];
        $rpt->lon = $report['lon'];
        $rpt->heading = $report['heading'];
        $rpt->altitude = $report['altitude'];
        $rpt->groundspeed = $report['groundspeed'];
        $rpt->phase = $report['phase'];
        $rpt->client = $report['client'];
        $rpt->save();
        return response()->json([
            'status' => 200
        ]);
    }
    public function getBids($user_id)
    {
        $bids = Bid::where('user_id', $user_id)->with('depapt')->with('arrapt')->with('airline')->with('aircraft')->get();
        $export = array();
        //dd($bids);
        $c = 0;
        foreach ($bids as $bid) {
            $export[$c]['bidid'] = $bid['id'];
            $export[$c]['routeid'] = $bid['id'];
            $export[$c]['code'] = $bid['airline']['icao'];
            $export[$c]['flightnumber'] = $bid['flightnum'];
            $export[$c]['type'] = "P";
            $export[$c]['departureicao'] = $bid['depapt']['icao'];
            $export[$c]['arrivalicao'] = $bid['arrapt']['icao'];
            $export[$c]['route'] = $bid['route'];
            $export[$c]['cruisingaltitude'] = "35000";
            $export[$c]['aircraft'] = $bid['aircraft_id'];
            $export[$c]['duration'] = '0.00';
            $export[$c]['departuretime'] = $bid['deptime'];
            $export[$c]['arrivaltime'] = $bid['arrtime'];
            $export[$c]['load'] = '0';
            $export[$c]['daysofweek'] = "0123456";
            // Iterate through the array
            $c++;
        }
        return response()->json($export);
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
            return Bid::where(['user_id' => $userid, 'airline_id' => $a->id, 'flightnum' => $ret['flightnum']])->first();

        }

        # Invalid flight number
        $ret['code'] = '';
        $ret['flightnum'] = $flightnum;
        return Bid::where(['user_id' => $userid, 'flightnum' => $ret['flightnum']])->first();
    }
}
