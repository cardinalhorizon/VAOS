<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 10/20/17
 * Time: 4:29 PM
 */

namespace App\VASystem;

use App\Models\AircraftGroup;
use App\Models\Airline;
use App\Models\Aircraft;
use App\Models\Airport;

class VAAircraft
{
    public static function createAircraft($data)
    {
        $acf = new Aircraft();
        //try
        //{
        $acf->icao = $data['icao'];
        $acf->name = $data['name'];
        $acf->manufacturer = $data['manufacturer'];
        $acf->registration = $data['registration'];
        $acf->range = $data['range'];
        $acf->maxpax = $data['maxpax'];
        $acf->maxgw = $data['maxgw'];
        $acf->status = $data['status'];

        // time for the optional stuff

        // If we have a hub, assiciate it.
        if (array_key_exists('hub', $data)) {
            $hub = Airport::find($data['hub']);
            $acf->hub()->associate($hub);

            // and while we're at it, lets set that as the current location

            $acf->location()->associate($hub);
        }
        if (array_key_exists('airline', $data)) {
            $air = Airline::find($data['airline']);

            $acf->airline()->associate($air);
        }

        // Now the extremely fun part. Figuring out the aircraft group.
        // Aircraft Groups are both User Defined and System Defined.
        // First, we want to check if there is an aircraft group that already exists for this type.
        //dd($acf);
        $acf->save();
        if (AircraftGroup::where(['icao' => $data['icao'], 'userdefined' => false ])->first() === null )
        {
            // We didn't find it so lets create one real quick
            $group = new AircraftGroup([
                'name' => $data['name'],
                'icao' => $data['icao'],
                'userdefined' => false
            ]);
            // now lets associate the aircraft with the new group.
            $group->save();
        }
        else
        {
            $group = AircraftGroup::where(['icao' => $data['icao'], 'userdefined' => false ])->first();
        }
        $acf->aircraft_group()->attach($group);
        //
        // Now that is done, lets check if we want to add it to a user defined group.
        if (array_key_exists('group', $data))
        {
            $acf->aircraft_group()->attach($data['group']);
        }

        // finally save the entire stack

        $acf->save();

        return true;
        //}
        //catch (\Exception $e)
        //{
        //    return false;
        //}
    }

    public static function updateAircraft($data, $id)
    {
        $acf = Aircraft::find($id);
        //try
        //{
        $acf->icao = $data['icao'];
        $acf->name = $data['name'];
        $acf->manufacturer = $data['manufacturer'];
        $acf->registration = $data['registration'];
        $acf->range = $data['range'];
        $acf->maxpax = $data['maxpax'];
        $acf->maxgw = $data['maxgw'];
        $acf->status = $data['status'];

        // time for the optional stuff

        // If we have a hub, assiciate it.
        if (array_key_exists('hub', $data)) {
            $hub = Airport::find($data['hub']);
            $acf->hub()->associate($hub);

            // and while we're at it, lets set that as the current location

            $acf->location()->associate($hub);
        }
        if (array_key_exists('airline', $data)) {
            $air = Airline::find($data['airline']);

            $acf->airline()->associate($air);
        }

        // Now the extremely fun part. Figuring out the aircraft group.
        // Aircraft Groups are both User Defined and System Defined.
        // First, we want to check if there is an aircraft group that already exists for this type.
        $acf->save();
        if (AircraftGroup::where(['icao' => $data['icao'], 'userdefined' => false ])->first() === null )
        {
            // We didn't find it so lets create one real quick
            $group = new AircraftGroup([
                'name' => $data['name'],
                'icao' => $data['icao'],
                'userdefined' => false
            ]);
            // now lets associate the aircraft with the new group.
            $group->save();
        }
        else
        {
            $group = AircraftGroup::where(['icao' => $data['icao'], 'userdefined' => false ])->first();
        }

        // Detach all existing aircraft group associations
        $acf->aircraft_group()->detach();

        // Reattach the aircraft group (may of changed due to aircraft ICAO change)
        $acf->aircraft_group()->attach($group);
        //
        // Now that is done, lets check if we want to add it to a user defined group.
        if (array_key_exists('group', $data))
        {
            $acf->aircraft_group()->attach($data['group']);
        }

        // finally save the entire stack

        $acf->save();

        return true;
        //}
        //catch (\Exception $e)
        //{
        //    return false;
        //}
    }
}