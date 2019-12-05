<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 5/13/18
 * Time: 8:24 PM.
 */

namespace App\Classes;

use App\Models\Flight as Flight;
use App\Models\FlightComment;

class VAOS_Flights
{
    public static function CreateNewFlight()
    {
        return E_USER_DEPRECATED;
    }

    public static function addGroupFlightToUser($user, $group_flight)
    {
        //
    }

    public static function duplicateFlight($user, $flight, $flightnum = null)
    {
        // first get the flight.
    }

    public static function fileReport($data)
    {
        $flight               = Flight::find($data['legacyroute']);
        $flight->landingrate  = $data['landingrate'];
        $flight->flighttime   = $data['flighttime'];
        $flight->acars_client = $data['source'];
        $flight->fuel_used    = $data['fuelused'];
        $flight->sc_log       = $data['log'];
        $flight->state        = 2;
        $flight->status       = 0;
        // Auto Accept System
        if (env('VAOS_AA_ENABLED')) {
            if ($data['landingrate'] >= env('VAOS_AA_LR')) {
                $flight->status = 1;
            }
        }
        if (env('VAOS_AA_ALL')) {
            $flight->status = 1;
        }

        $flight->save();
        // now let's take care of comments.
        $comment = new FlightComment();

        $comment->user()->associate($data['user_id']);
        $comment->flight()->associate($flight);
        $comment->type    = 1;
        $comment->comment = $data['comment'];
        $comment->save();
    }
}
