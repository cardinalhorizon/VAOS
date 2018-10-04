<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 7/1/18
 * Time: 3:20 PM.
 */

namespace App\Classes;

use App\Models\Flight;
use GuzzleHttp\Client;
use App\Models\FlightData as ACARSData;

class VATSIMData
{
    public static function run()
    {
        $allFlights = Flight::where('state', '<=', 1)->with('airline')->get();
        //dd($allFlights->count());

        // get the VATSIM data text file.
        try {
            $client = new Client();
            $res = $client->request('GET', 'http://vatsim.aircharts.org/vatsim-data.txt', [

            ])->getBody();

            // TIme to parse this bitch for data. First, let's break out all the active clients. That's the only thing we're worried about.
            $data_lines = explode("\n", $res);

            // Get a list of active flights in our system and let's find the flights on the VATSIM status

            foreach ($allFlights as $flight) {
                foreach ($data_lines as $line) {
                    if (strpos($line, $flight->airline->icao . $flight->flightnum) !== false) {
                        // Awesome, we found the flight. Now we need to split the array out and store the data.
                        $data = explode(':', $line);
                        // double check if it's a pre-file or not. If so, don't do anything.
                        if ($data[5] === null) {
                            break;
                        }
                        // Let's check the state of the flight. If it's filed, we need to set it as active
                        if ($flight['state'] === 0) {
                            $flight->state = 1;
                            $flight->save();
                        }

                        // Ok looks like we're getting valid data.
                        $rpt = new ACARSData();
                        $rpt->user()->associate($flight->user->id);
                        $rpt->flight()->associate($flight);
                        $rpt->lat = $data[5];
                        $rpt->lon = $data[6];
                        $rpt->heading = $data[38];
                        $rpt->altitude = $data[7];
                        $rpt->groundspeed = $data[8];
                        $rpt->phase = 'N/A';
                        $rpt->online = 'VATSIM';
                        $rpt->timeremaining = '0';
                        $rpt->client = 'vatsim';
                        $rpt->save();
                        // update the flight with the current tracking information.
                        $flight->lat = $data[5];
                        $flight->lon = $data[6];
                        //$flight->heading = $data[38];
                        $flight->altitude = $data[7];
                        $flight->gs = $data[8];
                        $flight->save();
                        break;
                    }
                }
            }
        }
        catch (Exception $e)
        {
            $client->request('POST', 'https://discordapp.com/api/webhooks/463090163002638346/RUSA8CiVZynwGjqs59lRTc1u4l3sFlI0oQOjjbaUREPCOkFQNr7Tj2D38dkXoq8TMsMU', [
                'form_params' => [
                    'content' => "Exception for VATSIM Data: ".$e
                ]
            ]);
        }
        /*
         */

        // ok that's all the active flights. Now just in case, let's double check to see if we can find any aircraft
        // that our members have in their personal fleet being flown, add those flights to the system.

        return 'ok';
    }
}
