<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 10/25/16
 * Time: 1:07 AM
 */

namespace App\Classes;

use App\Models\Airport;
use GuzzleHttp\Client;

class VAOS_Airports
{
    public static function AddAirport($icao)
    {
        // lets request the airport identifier from the central database
        $client = new Client();
        $res = $client->request('GET', 'http://fsvaos.net/api/data/airports', [
            'query' => [
                'icao' => $icao,
            ]
        ])->getBody();
        // Add the airport to the database
        $data = json_decode($res, true);
        $airport = new Airport();
        // return dd($icao);
        try {
            $airport->id = $data['airport']['id'];
            $airport->name = $data['airport']['name'];
            $airport->icao = $data['airport']['ident'];
            $airport->iata = $data['airport']['iata_code'];
            $airport->lat = $data['airport']['latitude_deg'];
            $airport->lon = $data['airport']['longitude_deg'];
            $airport->city = $data['airport']['municipality'];
            $airport->country = $data['airport']['iso_country'];
        }
        catch (Exception $e)
        {
            return dd($data);

        }
        $airport->save();
    }
}