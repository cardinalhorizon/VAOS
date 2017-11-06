<?php

namespace App\Repositories;

use App\Models\Aircraft;

class AircraftRepository extends BaseRepository
{
    protected $fieldSearchable = ['icao', 'type', 'name', 'manufacturer', 'registration', 'status', 'hub_id', 'location_id', 'airline_id'];

    /**
     * @return string
     */
    public function model()
    {
        return Aircraft::class;
    }
}
