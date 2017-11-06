<?php

namespace App\Repositories;

use App\Models\Airport;

class AirportRepository extends BaseRepository
{
        public function model()
        {
            return Airport::class;
        }
}