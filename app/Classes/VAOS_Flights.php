<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 5/13/18
 * Time: 8:24 PM
 */

namespace App\Classes;

use App\Models\Flight as Flight;

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
}