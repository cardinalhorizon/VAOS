<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 10/24/16
 * Time: 12:58 AM.
 */

namespace App\Classes;

use App\Models\AviationGroup;
use App\Models\Location;
use App\Models\Aircraft;
use App\Models\AircraftGroup;
use Illuminate\Support\Facades\DB;

/**
 * Aircraft Data Class for system handling.
 */
class VAOS_Aircraft
{
    /**
     * Creates a new aircraft within the database. Passes an array.
     *
     * @param $data
     *
     * @return bool
     */
    public static function createAircraft($data)
    {
        $acf = new Aircraft();
        //try
        $acf->icao         = $data['icao'];
        $acf->name         = $data['name'];
        $acf->manufacturer = $data['manufacturer'];
        $acf->registration = $data['registration'];
        $acf->status       = $data['status'];

        // time for the optional stuff

        // If we have a base, assiciate it.
        if (array_key_exists('base', $data)) {
            $hub = Location::find($data['base']);
            $acf->base()->associate($hub);

            // and while we're at it, lets set that as the current location

            $acf->location()->associate($hub);
        }
        $air = null;
        if (array_key_exists('airline', $data)) {
            //dd($data);
            $air = AviationGroup::where('id', $data['aviation_group'])->first();

            $acf->airline()->associate($air);
        }

        // Now the extremely fun part. Figuring out the aircraft group.
        // Aircraft Groups are both User Defined and System Defined.
        // First, we want to check if there is an aircraft group that already exists for this type.
        //dd($acf);
        $acf->save();
        //dd($acf);
        if (DB::table('aircraft_groups')->where([
                    ['icao', '=', $data['icao']],
                    ['userdefined', '=', 'false'],
                    ['airline_id', '=', $air->id],
                ])->first() === null) {
            // We didn't find it so lets create one real quick
            $group = new AircraftGroup([
                    'name'        => $data['name'],
                    'icao'        => $data['icao'],
                    'userdefined' => false,
                ]);
            $group->airline()->associate($air);
            // now lets associate the aircraft with the new group.
            $group->save();
        } else {
            $group = AircraftGroup::where(['icao' => $data['icao'], 'userdefined' => false, 'airline_id' => $air->id])->first();
        }
        $acf->aircraft_group()->attach($group);
        //
        // Now that is done, lets check if we want to add it to a user defined group.
        if (array_key_exists('group', $data)) {
            $acf->aircraft_group()->attach($data['group']);
        }
        // finally save the entire stack
        $acf->save();
        return true;
    }

    public static function updateAircraft($data, $id)
    {
        $acf = Aircraft::find($id);
        $acf->icao         = $data['icao'];
        $acf->name         = $data['name'];
        $acf->manufacturer = $data['manufacturer'];
        $acf->registration = $data['registration'];
        $acf->status       = $data['status'];

        // time for the optional stuff
        $air = null;
        // If we have a base, assiciate it.
        if (array_key_exists('base', $data)) {
            $hub = Location::find($data['base']);
            $acf->base()->associate($hub);

            // and while we're at it, lets set that as the current location

            $acf->location()->associate($hub);
        }
        if (array_key_exists('aviation_group', $data)) {
            $air = AviationGroup::where('icao', $data['aviation_group'])->first();

            $acf->airline()->associate($air);
        }

        // Now the extremely fun part. Figuring out the aircraft group.
        // Aircraft Groups are both User Defined and System Defined.
        // First, we want to check if there is an aircraft group that already exists for this type.
        $acf->save();
        if (AircraftGroup::where(['icao' => $data['icao'], 'userdefined' => false, 'aviation_group_id' => $air])->first() === null) {
            // We didn't find it so lets create one real quick
            $group = new AircraftGroup([
                'name'        => $data['name'],
                'icao'        => $data['icao'],
                'aviation_group_id'  => $air->id,
                'userdefined' => false,
            ]);
            // now lets associate the aircraft with the new group.
            $group->save();
        } else {
            $group = AircraftGroup::where(['icao' => $data['icao'], 'userdefined' => false])->first();
        }

        // Detach all existing aircraft group associations
        $acf->aircraft_group()->detach();

        // Reattach the aircraft group (may of changed due to aircraft ICAO change)
        $acf->aircraft_group()->attach($group);
        //
        // Now that is done, lets check if we want to add it to a user defined group.
        if (array_key_exists('group', $data)) {
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

    public function deleteAircraft($id)
    {
        // First remove the aircraft from the aircraft group.

        $aircraft = Aircraft::find($id);

        $aircraft->aircraft_group()->detach();
        $aircraft->delete();

        return true;
    }
}
