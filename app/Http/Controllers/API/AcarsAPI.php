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
use stdClass;


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

    /**
     * Provide the live ACARS date for external apps in the VAOS way and a phpVMS Legacy Compatibility Function.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public static function getAcarsData(Request $request){

        $flights  = ACARSData::with(['bid.airline', 'bid.aircraft', 'bid.depapt', 'bid.arrapt', 'user'])->get();

        if ($request->query('format') == 'phpVMS') {

            /**
             * So here we are to make the magic happen that all external apps which use the old phpVMS format, work again
             * Treat it with respect, this won't be around for too much longer.
             */

            if(!$flights)
                $flights = array();

            $acarsflights = [];

            foreach ($flights as $flight) {

                if($flight->bid->route == '') {
                    $flight->bid->route_details = array();
                } else {

                    /**
                     * This is some old phpVMS shit to get the navdata for the route.
                     * Does not work in VAOS yet. Will maybe become a feature in an upcoming update
                     */

                    /*
                    #declare stClass (php 5.5)
                    $params = new stdClass();
                    # Jeff's fix for ACARS
                    $params->deplat = $flight->deplat;
                    $params->deplng = $flight->deplng;
                    $params->route = $flight->route;
                    $flight->route_details = NavData::parseRoute($params);
                    */
                    // No NavData so empty array instead
                    $flight->bid->route_details = array();
                }

                /**
                 * Some other unnecessary function, because all ACARS app's
                 * provide heading nowadays. This function is in here
                 * for the sake of completeness
                 */

                /* If no heading was passed via ACARS app then calculate it
				This should probably move to inside the ACARSData function, so then
				 the heading is always there for no matter what the calculation is
				*/

                if($flight->heading == '') {
                    /* Calculate an angle based on current coords and the
                        destination coordinates */
                    $flight->heading = intval(atan2(($flight->lat - $flight->bid->arrapt->lat), ($flight->lon - $flight->bid->arrapt->lon)) * 180 / 3.14);
                    //$flight->heading *= intval(180/3.14159);
                    if(($flight->lon - $flight->bid->arrapt->lon) < 0) {
                        $flight->heading += 180;
                    }
                    if($flight->heading < 0) {
                        $flight->heading += 360;
                    }
                }


                $data = new stdClass();
                $data->aircraft = (string) $flight->bid->aircraft->id;
                $data->aircraftname = $flight->bid->aircraft->name;
                $data->alt = $flight->altitude;
                $data->arrapt = $flight->bid->arrapt->name;
                $data->arricao = $flight->bid->arrapt->icao;
                $data->arrlat = (string) $flight->bid->arrapt->lat;
                $data->arrlng = (string) $flight->bid->arrapt->lon;
                $data->arrname = $flight->bid->arrapt->name;
                $data->arrtime = $flight->bid->arrtime;
                $data->client = $flight->client;
                $data->code = $flight->bid->airline->icao;
                $data->depapt = $flight->bid->depapt->name;
                $data->depicao = $flight->bid->depapt->icao;
                $data->deplat = (string) $flight->bid->depapt->lat;
                $data->deplng = (string) $flight->bid->depapt->lon;
                $data->depname = $flight->bid->depapt->name;
                $data->deptime = $flight->bid->deptime;
                $data->distremain = $flight->distremaining;
                $data->distremaining = $flight->distremaining;
                $data->firstname = $flight->user->first_name;
                $data->flightnum = $flight->bid->airline->icao.$flight->bid->flightnum;
                $data->gs = (string) $flight->groundspeed;
                $data->heading = (string) $flight->heading;
                $data->id = (string) $flight->user->id; // phpVMS is this the user ID or something else?
                $data->lastname = $flight->user->last_name;
                $data->lastupdate = (string) $flight->updated_at;
                $data->lat = (string) $flight->lat;
                $data->lng = (string)$flight->lon;
                $data->online = $flight->online;
                $data->phasedetail = $flight->phase;
                $data->pilotid = $flight->user->pilotid; // TODO Make pilot ID work
                $data->pilotname = $flight->user->first_name.' '.$flight->user->last_name;
                $data->registration = $flight->bid->aircraft->registration;
                $data->route = $flight->bid->route;
                $data->route_details = $flight->bid->route_details;
                $data->timeremaining = $flight->timeremaining;


                $c = (array)$data;

                /**
                 * phpVMS and VAOS normalize some data
                 */

                // Normalize the data
                if($c['timeremaining'] == '') {
                    $c['timeremaining'] =  '-';
                }
                if(trim($c['phasedetail']) == '') {
                    $c['phasedetail'] = 'Enroute';
                }

                //VAOS normalize the data. This will be removed when the Todos are done

                if ($c['route'] == ''){
                    $c['route'] = '';
                }

                $acarsflights[] = $c; // Convert the object to an array
            }

            return json_encode($acarsflights); // Convert to json format

        }else{
            return response()->json([
                'status' => 200,
                'ACARSData' => $flights
            ]);
        }
    }
}
