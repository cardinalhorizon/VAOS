<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 10/25/16
 * Time: 1:07 AM.
 */

namespace App\Classes;

use GuzzleHttp\Client;
use App\Models\Airport;
use Illuminate\Support\Facades\Log;

class VAOS_Airports
{
    public static function checkOrAdd($input)
    {
        Log::debug('Calling checkOrAdd');
        $apt = Airport::where('id', $input->id)->first();
        if ($apt === null) {
            $id = VAOS_Airports::addWithObject($input);
            return $id;
        }
        else {
            return $apt;
        }

    }
    public static function addWithObject($apt)
    {
        //dd($apt);
        $airport = new Airport();
        $airport->id         = $apt->id;
        $airport->name       = $apt->name;
        $airport->icao       = $apt->gps_code;
        $airport->iata       = $apt->iata_code;
        $airport->lat        = $apt->latitude_deg;
        $airport->lon        = $apt->longitude_deg;
        $airport->city       = $apt->municipality;
        $airport->country    = $apt->iso_country;

        $airport->save();
        Log::debug('Created Airport: '.$airport->icao);
        return $airport;
    }
    public static function AddAirport($icao)
    {
        // lets request the airport identifier from the central database
        $client = new Client();
        $res    = $client->request('GET', 'http://fsvaos.net/api/data/airports', [
            'query' => [
                'icao' => $icao,
            ],
        ])->getBody();
        // Add the airport to the database
        $data    = json_decode($res, true);
        $airport = new Airport();
        // return dd($icao);
        if ($data['status'] !== 200) {
            abourt(404, 'Airport Was Not Found in Master Database. Please Contact Support');
        }
        try {
            $airport->id         = $data['airport']['id'];
            $airport->name       = $data['airport']['name'];
            $airport->icao       = $data['airport']['gps_code'];
            $airport->iata       = $data['airport']['iata_code'];
            $airport->lat        = $data['airport']['latitude_deg'];
            $airport->lon        = $data['airport']['longitude_deg'];
            $airport->city       = $data['airport']['municipality'];
            $airport->country    = $data['airport']['iso_country'];

            // Add the Banner URL if we got it
            if (isset($data['airport']['banner_url'])) {
                $airport->banner_url = $data['airport']['banner_url'];
            }
        } catch (Exception $e) {
            return dd($data);
        }
        $airport->save();

        return $airport->id;
    }
}
