<?php

namespace App\Classes;

use App\Models\Flight;
use App\Models\FlightData;
use App\Models\User;
use GuzzleHttp\Client;

class VATSIM_Data
{
    public $region = 'us';

    public function __construct($region = null)
    {
        if (! is_null($region)) {
            $this->region = $region;
        }
    }

    public function get_data()
    {
        // Ok, get the data
        $client      = new Client();
        $vatsim_data = json_decode($client->request('GET', 'http://'.$this->region.'.data.vatsim.net/vatsim-data.json', [])->getBody(), true);

        // Instead of querying like crazy, let's pull down the active flights.

        $users = User::whereNotNull('vatsim')->with(['flights' => function ($query) {
            $query->where('state', '<=', 1);
        }])->with('aircraft')->get();
        // Ok, split the clients by server

        foreach ($users as $user) {
            // First, let's see if there's an active flight on VATSIM
            foreach ($vatsim_data['pilots'] as $vatsim_flight) {
                if ($vatsim_flight['member']['cid'] === $user['vatsim']) {
                    // Ok we found an active connection. See if it corresponds to a flight.

                    // Boolean value checks to see if there's an active flight for him. Important for personal aircraft tracking.
                    $in_flights = false;
                    foreach ($user->flights as $flight) {
                        if ($vatsim_flight['callsign'] === $flight['callsign']) {
                            $in_flights = true;
                            // if it's not active, well it's active now.
                            if ($flight['state'] === 0) {
                                $flight->state = 1;
                                $flight->save();
                            }
                            self::addPositionReport($flight, $vatsim_flight);
                            break;
                        }
                    }
                    break;
                }
            }
        }
    }

    private function addPositionReport($flight, $data)
    {
        $fd         = new FlightData();
        $fd->data   = $data;
        $fd->source = 'VATSIM';
        $fd->user()->associate($flight['user']['id']);
        $fd->flight()->associate($flight['id']);
        $fd->save();
    }
}
