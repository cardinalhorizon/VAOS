<?php

use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Models\Airline::class, function (Faker $faker) {
    return [
        'fshub_id' => '001',
        'icao'     => 'AUA',
        'iata'     => 'OS',
        'name'     => 'Austrian Airlines',
        'callsign' => 'AUSTRIAN',
    ];
});
