<?php

namespace App\Http\Controllers\OnlineData;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\FlightData as ACARSData;
use GuzzleHttp\Client;

class VatsimData extends Controller
{
    public function updateAll()
    {
        $allFlights = Flight::active()->filed()->with('airline')->get();

        foreach ($allFlights as $flight) {
            $flight->combi = $flight->airline->icao.$flight->flightnum;
            //dd($flight);
        }
        // get the VATSIM data text file.
        $client = new Client();
        $res    = $client->request('GET', 'http://info.vroute.net/vatsim-data.txt', [

        ])->getBody();

        // TIme to parse this bitch for data. First, let's break out all the active clients. That's the only thing we're worried about.
        $data_lines = explode("\n", $res);

        // Get a list of active flights in our system and let's find the flights on the VATSIM status

        foreach ($allFlights as $flight) {
            foreach ($data_lines as $line) {
                if (strpos($line, $flight->combi) !== false) {
                    // Awesome, we found the flight. Now we need to split the array out and store the data.
                    $data = explode(':', $line);
                    // double check if it's a pre-file or not. If so, don't do anything.
                    if ($data[5] === null) {
                        break;
                    }

                    // Let's check the state of the flight. If it's filed, we need to set it as active
                    if ($flight->state = 0) {
                        $flight->state = 1;
                    }

                    // Ok looks like we're getting
                    //valid data.
                    $rpt = new ACARSData();
                    dd(true);
                    $rpt->user()->associate($flight->user->id);
                    $rpt->flight()->associate($flight);
                    $rpt->lat           = $data[5];
                    $rpt->lon           = $data[6];
                    $rpt->heading       = $data[38];
                    $rpt->altitude      = $data[7];
                    $rpt->groundspeed   = $data[8];
                    $rpt->phase         = 'N/A';
                    $rpt->online        = true;
                    $rpt->timeremaining = '0';
                    $rpt->client        = 'vatsim';
                    $rpt->save();

                    // update the flight with the current tracking information.
                    unset($flight['combi']);
                    $flight->lat      = $data[5];
                    $flight->lon      = $data[6];
                    $flight->heading  = $data[38];
                    $flight->altitude = $data[7];
                    $flight->gs       = $data[8];
                    $flight->save();
                    break;
                }
            }
        }

        // ok that's all the active flights. Now just in case, let's double check to see if we can find any aircraft
        // that our members have in their personal fleet being flown, add those flights to the system.

        return 'ok';
    }
}
