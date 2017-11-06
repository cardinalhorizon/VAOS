<?php

namespace App\Repositories;

use App\Models\Airline;

class AirlineRepository extends BaseRepository
{
    protected $fieldSearchable = ['icao', 'fshub_id', 'iata', 'name', 'callsign'];

    /**
     * @return string
     */
    public function model()
    {
        return Airline::class;
    }
}
