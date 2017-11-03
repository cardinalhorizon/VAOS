<?php

namespace Tests\Feature;

use App\Models\Airline;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AirlineCreateTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function user_can_create_an_airline()
    {
        $airline = factory(Airline::class)->make([
            'fshub_id' => '001',
            'icao' => 'AUA',
            'iata' => 'OS',
            'name' => 'Austrian Airlines',
            'callsign' => 'AUSTRIAN'
        ]);

        $this->post('/airline', $airline->toArray());

        $this->get('/airline')
            ->assertSee($airline->name)
            ->assertSee($airline->icao)
            ->assertSee($airline->iata)
            ->assertSee($airline->callsign);

    }
}
