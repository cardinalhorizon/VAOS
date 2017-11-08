<?php

namespace Tests\Feature;

use App\Models\Airline;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AirlineListTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function user_can_see_arline_listing()
    {

        $airline = factory(Airline::class)->create([
            'fshub_id' => '001',
            'icao' => 'AUA',
            'iata' => 'OS',
            'name' => 'Austrian Airlines',
            'callsign' => 'AUSTRIAN'
        ]);

        $this->get('/airline')
            ->assertSee($airline->name)
            ->assertSee($airline->icao)
            ->assertSee($airline->iata)
            ->assertSee($airline->callsign);

    }
}
