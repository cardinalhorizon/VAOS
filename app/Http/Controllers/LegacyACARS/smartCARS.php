<?php

namespace App\Http\Controllers\LegacyACARS;

use App\User;
use App\Models\Flight;
use GuzzleHttp\Client;
use App\Models\Airline;
use App\Models\FlightData as ACARSData;
use Illuminate\Http\Request;
use App\Models\FlightComment;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class smartCARS extends Controller
{
    public function filePIREP(Request $request)
    {
        // first lets check to see if we have everything required for the request
        $input = $request->all();
        $data  = [];

        // First lets update financial data.

        // TODO: Make Financial Data Calls and Updates

        // TODO: Check Auto Accept PIREP Values

        // TODO: Add PIREP To Database

        // first let's retrieve the original bid from the database and enter in all the values\

        // This is a legacy ACARS client. Treat it with respect, they won't be around
        // for too much longer. All we need is the user data, flight info and we are all set
        $pirep               = Flight::find($request->input('legacyroute'));
        $pirep->landingrate  = $request->input('landingrate');
        $pirep->flighttime   = $request->input('flighttime');
        $pirep->acars_client = $request->input('source');
        $pirep->fuel_used    = $request->input('fuelused');
        $pirep->flight_data  = $request->input('log');
        $pirep->state        = 2;
        // Auto Accept System
        if (env('VAOS_AA_ENABLED')) {
            if ($request->input('landingrate') >= env('VAOS_AA_LR')) {
                $pirep->status = 1;
            }
        }
        if (env('VAOS_AA_ALL')) {
            $pirep->status = 1;
        }

        $pirep->save();
        // now let's take care of comments.
        $comment = new FlightComment();

        $comment->user()->associate($request->input('pilotid'));
        $comment->flight()->associate($pirep);
        $comment->comment = $request->input('comment');
        $comment->save();

        return response()->json([
            'status' => 200,
        ]);
    }

    public function positionreport(Request $request)
    {
        $report = [];
        // First off, lets establish the format. Is this phpvms?
        if ($request->query('format') == 'phpVMS') {
            // well shoot, we got a legacy ACARS client. Let's sterilize the data and format the input.
            $report['user']    = User::find($request->input('pilotid'))->id;
            $report['user_id'] = $request->input('pilotid');
            // split the flight string the phpVMS way into Airline Code and Flight Number.
            // Why they did this is beyond me. Foreign keys are another story.....
            // phpVMS is a pretty little princess
            $flight        = self::getProperFlightNum($request->input('flightnum'), $request->input('pilotid'));
            $report['bid'] = $flight['id'];
            //dd($report['bid']);
            // phpVMS sends the aircraft ID from database. Let's use it to our advantage.
            //$report['aircraft'] = Aircraft::where('registration', $request->input('registration'))->first();
            $report['lat'] = $request->input('lat');
            // Lets convert lng to lon. Play with the big boys now
            $report['lon']           = $request->input('lng');
            $report['heading']       = $request->input('heading');
            $report['altitude']      = $request->input('alt');
            $report['groundspeed']   = $request->input('gs');
            $report['distremain']    = $request->input('distremain');
            $report['timeremaining'] = $request->input('timeremaining');
            $report['online']        = $request->input('online');
            /*
            'deptime'
            'arrtime'
            'route'
            */
            $report['phase']  = $request->input('phasedetail');
            $report['client'] = $request->input('client');
        } else {
            return response()->json([
                'status' => 800,
            ]);
        }
        // if the flight is not activated, activate it!
        if ($flight['state'] === 0) {
            $flight->state = 1;
            $flight->save();
        }
        // find if the row exists

        $rpt = new ACARSData();
        $rpt->user()->associate($report['user']);
        $rpt->flight()->associate($report['bid']);
        $rpt->lat           = $report['lat'];
        $rpt->lon           = $report['lon'];
        $rpt->heading       = $report['heading'];
        $rpt->altitude      = $report['altitude'];
        $rpt->groundspeed   = $report['groundspeed'];
        $rpt->phase         = $report['phase'];
        $rpt->client        = $report['client'];
        //$rpt->distremain    =  $report['distremain'];
        $rpt->timeremaining = $report['timeremaining'];
        $rpt->online        =  $report['online'];
        $rpt->save();

        $flight->lat = $report['lat'];
        $flight->lon = $report['lon'];
        //$flight->heading = $data[38];
        $flight->altitude = $report['altitude'];
        $flight->gs = $report['groundspeed'];
        $flight->save();
        /*
        $client = new Client();
        $client->request('POST', 'https://discordapp.com/api/webhooks/463090163002638346/RUSA8CiVZynwGjqs59lRTc1u4l3sFlI0oQOjjbaUREPCOkFQNr7Tj2D38dkXoq8TMsMU', [
            'form_params' => [
                'content' => "**DEBUG**: Position Report: ".$report['user']." Flight ID: ".$report['bid']
            ]
        ]);
        */
        return response()->json([
            'status' => 200,
        ]);
    }

    public function getBids($user_id)
    {
        $flights = Flight::where(['user_id' => $user_id, ['state', '<=', 1]])->with('depapt')->with('arrapt')->with('airline')->with('aircraft')->get();
        $export  = [];
        //dd($flights);
        $c = 0;
        foreach ($flights as $flight) {
            $export[$c]['bidid']            = $flight['id'];
            $export[$c]['routeid']          = $flight['id'];
            $export[$c]['code']             = $flight['airline']['icao'];
            $export[$c]['flightnumber']     = $flight['flightnum'];
            $export[$c]['type']             = 'P';
            $export[$c]['departureicao']    = $flight['depapt']['icao'];
            $export[$c]['arrivalicao']      = $flight['arrapt']['icao'];
            $export[$c]['route']            = $flight['route'];
            $export[$c]['cruisingaltitude'] = '35000';
            $export[$c]['aircraft']         = $flight['aircraft_id'];
            $export[$c]['duration']         = '0.00';
            $export[$c]['departuretime']    = $flight['deptime'];
            $export[$c]['arrivaltime']      = $flight['arrtime'];
            $export[$c]['load']             = '0';
            $export[$c]['daysofweek']       = '0123456';
            // Iterate through the array
            $c++;
        }

        return response()->json($export);
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
            return Flight::where(['user_id' => $userid, 'airline_id' => $a->id, 'flightnum' => $ret['flightnum']])->where('state', '<=', '1')->first();
        }

        // Invalid flight number
        $ret['code']      = '';
        $ret['flightnum'] = $flightnum;

        return Flight::where(['user_id' => $userid, 'flightnum' => $ret['flightnum']])->where('state', '<=', '1')->first();
    }
}
