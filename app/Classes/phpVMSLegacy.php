<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 11/11/16
 * Time: 6:07 PM
 */

namespace App\Classes;
use App\Airline;
use App\Bid;
/**
 * Helper Class that converts phpVMS formatted data into VAOS friendly stuff.
 * Class phpVMSLegacy
 * @package App\Classes
 */
class phpVMSLegacy
{
    public static function getFlightBid($flightnum, $userid) {
        if ($flightnum == '') return false;

        $ret = array();
        $flightnum = strtoupper($flightnum);
        $airlines = Airline::all();

        foreach ($airlines as $a) {
            $a->icao = strtoupper($a->icao);

            if (strpos($flightnum, $a->icao) === false) {
                continue;
            }

            $ret['icao'] = $a->icao;
            $ret['flightnum'] = str_ireplace($a->icao, '', $flightnum);

            // ok now that we deduced that, let's find the bid.


            return Bid::where(['user_id' => $userid, 'airline_id' => $a->id, 'flightnum' => $ret['flightnum']])->first();
        }

        # Invalid flight number
        $ret['code'] = '';
        $ret['flightnum'] = $flightnum;
        return Bid::where(['user_id' => $userid, 'flightnum' => $ret['flightnum']])->first();
    }
}