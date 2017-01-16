<?php

namespace App\Http\Controllers\API;

use App\ACARSData;
use App\Airline;
use App\Bid;
use App\Models\Aircraft;
use App\Models\Airport;
use App\ScheduleComplete;
use App\User;
use Illuminate\Http\Request;


class AcarsAPI extends Controller
{
    public function position(Request $request)
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

    /**
     * phpVMS Legacy Compatibility Function. Splits the flightnum string into code and number.
     * @param $flightnum
     * @return array|bool
     */
    public static function getProperFlightNum($flightnum, $userid) {
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
