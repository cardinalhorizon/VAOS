<?php

namespace App\Http\Controllers\API;

use App\Classes\VAOS_Aircraft;
use App\AircraftGroup;
use App\Airline;
use App\Models\Aircraft;
use App\Models\Airport;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

use App\Http\Requests;

class FleetAPI extends Controller
{
    public function showAll()
    {
        return json_encode(['status' => 200, 'aircraft' => Aircraft::all()]);
    }
    public function addAircraft(Request $request)
    {
        // POST http://airline.com/api/1_0/fleet
        // First lets check to see if we are adding via local provided data or from Central's database
        if ($request->query('source') == 'local')
        {
            // This route is to add an aircraft providing ALL local values. This will be fun!!!

            // lets steralize the array before sending it to the class. Things can get messy
            $data = array();
            $data['icao'] = $request->input('icao');
            $data['name'] = $request->input('name');
            $data['manufacturer'] = $request->input('manufacturer');
            $data['registration'] = $request->input('registration');
            $data['range'] = $request->input('range');
            $data['maxpax'] = $request->input('maxpax');
            $data['maxgw'] = $request->input('maxgw');
            $data['enabled'] = $request->input('enabled');
            $data['hub'] = $request->input('hub');
            $data['airline'] = $request->input('airline');
            $data['group'] = $request->input('group');
            //dd(AircraftGroup::where(['icao' => $data['icao'], 'userdefined' => false ])->first());
            // $result = AircraftData::createAircraft($data);
        }
        else
        {
            // Lets Call Home to retrieve our data. Much easier!!
            $client = new Client();

            $res = $client->request('GET', 'http://fsvaos.net/api/central/aircraft', [
                'query' => [
                    'icao' => $request->input('icao'),
                ]
            ])->getBody();

            if ($res['status'] == 404)
            {
                // well the main server is a useless piece of shit so lets inform the user that.
                return response()->json([
                    'status' => 501
                ]);
            }
            // lets steralize the array before sending it to the class. Things can get messy
            $data = array();
            $data['icao'] = $res['aircraft']['icao'];
            $data['name'] = $res['aircraft']['name'];
            $data['manufacturer'] = $res['aircraft']['manufacturer'];
            $data['registration'] = $request->input('registration');
            $data['range'] = $res['aircraft']['range'];
            $data['maxpax'] = $res['aircraft']['maxpax'];
            $data['maxgw'] = $res['aircraft']['maxgw'];
            $data['enabled'] = $request->input('enabled');


            // $result = AircraftData::createAircraft($data);
        }

        $acf = new Aircraft();

        $acf->icao = $data['icao'];
        $acf->name = $data['name'];
        $acf->manufacturer = $data['manufacturer'];
        $acf->registration = $data['registration'];
        $acf->range = $data['range'];
        $acf->maxpax = $data['maxpax'];
        $acf->maxgw = $data['maxgw'];
        $acf->enabled = $data['enabled'];

        // time for the optional stuff

        // If we have a hub, assiciate it.
        if ($data['hub'] != null) {
            $hub = Airport::find($data['hub']);
            $acf->hub()->associate($hub);

            // and while we're at it, lets set that as the current location

            $acf->location()->associate($hub);
        }
        if ($data['airline'] != null) {
            $air = Airline::where('icao', $data['airline'])->first();

            $acf->airline()->associate($air);
        }

        // finally save the entire stack

        $acf->save();

        // Now the extremely fun part. Figuring out the aircraft group.
        // Aircraft Groups are both User Defined and System Defined.
        // First, we want to check if there is an aircraft group that already exists for this type.

        $sysgrp = AircraftGroup::where(['icao' => $data['icao'], 'userdefined' => false ])->first();

        if ($sysgrp === null )
        {
            // We didn't find it so lets create one real quick
            $group = new AircraftGroup([
                'name' => $data['name'],
                'icao' => $data['icao'],
                'userdefined' => false
            ]);
            // now lets associate the aircraft with the new group.
            $group->save();
            $acf->aircraft_group()->attach($group);
        }
        else
        {
            // ok now that we know that the group exists, lets add it to the already existing system group.
            $acf->aircraft_group()->attach($sysgrp);
        }
        // Now that is done, lets check if we want to add it to a user defined group.
        if ($data['group'] != null)
        {
            $acf->aircraft_group()->attach($data['group']);
        }

        return response()->json([
            'status' => 200
        ]);
    }
}
