<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 1/1/18
 * Time: 7:20 PM.
 */

namespace App\Classes;

use App\Models\AviationGroup;

class VAOS_AviationGroups
{
    public function newAviationGroup($data)
    {
        // Create new Instance
        $group = new AviationGroup();
        // Set Fields
        $group->icao = $data['icao'];
        $group->name = $data['name'];
        // Save it off
        $group->save();
    }

    public function addUserToAirline($airline_id, $user_id)
    {
        //
    }
}
