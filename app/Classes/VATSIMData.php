<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 7/1/18
 * Time: 3:20 PM.
 */

namespace App\Classes;

use App\Models\Flight;
use App\Models\FlightData as ACARSData;
use App\User;
use GuzzleHttp\Client;

class VATSIMData
{
    public static function run()
    {
        //$allFlights = Flight::where('state', '<=', 1)->with('airline')->get();
        //dd($allFlights->count());

        $users = User::whereNotNull('vatsim')->with(['flights' => function ($query) {
            $query->where('state', '<=', 1);
        }])->with('aircraft')->get();
        //dd($users);
        // get the VATSIM data text file.
        try {
            $client = new Client();
            $res    = $client->request('GET', 'http://info.vroute.net/vatsim-data.txt', [

            ])->getBody();

            $online_active  = [];
            $online_prefile = [];
            // TIme to parse this bitch for data. First, let's break out all the active clients. That's the only thing we're worried about.
            $data_lines = explode("\n", $res);
            // find the line with all the active data.
            $start_active  = null;
            $start_prefile = null;

            // Find where we need to start within the file to get the required data.
            foreach ($data_lines as $key=>$value) {
                if ($value === "!CLIENTS:\r") {
                    $start_active = $key;
                    $start_active++;
                    continue;
                }
                if ($value === "!PREFILE:\r") {
                    $start_prefile = $key;
                    $start_prefile++;
                    continue;
                }
            }

            foreach ($data_lines as $key=>$line) {
                if ($key <= $start_active) {
                    continue;
                }
                if ($line === ";\r") {
                    continue;
                }
                if ($line === "!SERVERS:\r") {
                    break;
                }
                array_push($online_active, explode(':', $line));
            }

            foreach ($data_lines as $key=>$line) {
                if ($key <= $start_prefile) {
                    continue;
                }
                if ($line === ";\r" || $line === ";   END\r") {
                    continue;
                }
                if ($line === '') {
                    continue;
                }
                array_push($online_prefile, explode(':', $line));
            }

            // Now that we have our data nice and organized
            // First, find if they're doing an Aviation Group Flight.

            foreach ($users as $user) {
                foreach ($online_active as $data) {
                    if ($data[1] === $user->vatsim) {
                        // Ok he's connected. Check if we have his flight. If we do, add the data.
                        $in_flights = false;
                        if ($data[2] === 'ATC') {
                            break;
                        }

                        foreach ($user->flights as $flight) {
                            if ($data[0] === $flight->callsign) {
                                $in_flights = true;
                                // if it's not active, well it's active now.
                                if ($flight['state'] === 0) {
                                    $flight->state = 1;
                                    $flight->save();
                                }

                                // Ok looks like we're getting valid data.
                                $rpt = new ACARSData();
                                $rpt->user()->associate($flight->user->id);
                                $rpt->flight()->associate($flight);
                                $rpt->lat           = $data[5];
                                $rpt->lon           = $data[6];
                                $rpt->heading       = $data[38];
                                $rpt->altitude      = $data[7];
                                $rpt->groundspeed   = $data[8];
                                $rpt->phase         = 'N/A';
                                $rpt->online        = 'VATSIM';
                                $rpt->timeremaining = '0';
                                $rpt->client        = 'vatsim';
                                $rpt->save();
                                // update the flight with the current tracking information.
                                $flight->lat      = $data[5];
                                $flight->lon      = $data[6];
                                $flight->heading  = $data[38];
                                $flight->altitude = $data[7];
                                $flight->gs       = $data[8];
                                $flight->save();
                                break;
                            }
                        }
                        if (! $in_flights) {
                            // Ok, check if the callsign is with his personal aircraft.
                            foreach ($user->aircraft as $aircraft) {
                                if ($data[0] === $aircraft->registration) {
                                    // Ok he's flying his personal aircraft. Does he have a flight plan filed???
                                    // For this check, we're going to look at the plan type.
                                    if ($data[21] === '') {
                                        continue;
                                    }
                                    // Ok he has a plan, but a flight is not created, so create the flight.
                                    $flight = self::createFlightFromPlan($data, $user, $aircraft, 1);

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
                        break;
                    }
                }
                // Now let's check if there's no flight in the system and it needs to be created.
                foreach ($online_prefile as $data) {
                    if ($data[1] === $user->vatsim) {
                        // Ok so he's prefiled.
                        $in_flights = false;
                        if ($data[2] === 'ATC') {
                            break;
                        }

                        foreach ($user->flights as $flight) {
                            if ($data[0] === $flight->callsign) {
                                $in_flights = true;
                                // if it's not active, well it's active now.
                                break;
                            }
                        }
                        if (! $in_flights) {
                            // Ok, check if the callsign is with his personal aircraft.
                            foreach ($user->aircraft as $aircraft) {
                                if ($data[0] === $aircraft->registration) {
                                    // Ok he's flying his personal aircraft. Does he have a flight plan filed???
                                    // For this check, we're going to look at the plan type.
                                    if ($data[21] === '') {
                                        continue;
                                    }
                                    // Ok he has a plan, but a flight is not created, so create the flight.
                                    self::createFlightFromPlan($data, $user, $aircraft);
                                }
                            }
                        }
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            $client->request('POST', 'https://discordapp.com/api/webhooks/463090163002638346/RUSA8CiVZynwGjqs59lRTc1u4l3sFlI0oQOjjbaUREPCOkFQNr7Tj2D38dkXoq8TMsMU', [
                'form_params' => [
                    'content' => 'Exception for VATSIM Data: '.$e->getMessage().' on line '.$e->getLine(),
                ],
            ]);
            dd($e);
        }
        /*
         */

        // ok that's all the active flights. Now just in case, let's double check to see if we can find any aircraft
        // that our members have in their personal fleet being flown, add those flights to the system.

        return 'ok';
    }

    private static function createFlightFromPlan($data, $user, $aircraft, $state = 0)
    {
        $flight = new Flight();

        $flight->aircraft()->associate($aircraft);
        $flight->depapt()->associate(VAOS_Airports::checkOrAdd($data[11]));
        $flight->arrapt()->associate(VAOS_Airports::checkOrAdd($data[13]));
        //dd($acfgrp);

        $flight->user()->associate($user);

        // Lets JSON decode the defaults so we can place the route correctly within the system.

        $flight->callsign  = $data[0];
        $flight->route     = $data[30];
        // Now lets encode the cruise altitude in the JSON
        // store it

        $flight->deptime = null;
        $flight->arrtime = null;
        $flight->load    = 0;
        $flight->state   = $state;
        $flight->type    = 90;
        $flight->save();

        return $flight;
    }
}
