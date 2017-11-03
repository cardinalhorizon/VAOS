<?php

namespace Tests\Feature;

use App\Models\Airline;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AirlineTest extends TestCase
{
    use RefreshDatabase;

    protected $airlinerepo;

    protected function setUp()
    {
        parent::setUp();
        $this->airlinerepo = app('App\Repositories\AirlineRepository');
    }

    /** @test */
    function user_can_see_arline_listing()
    {
        $airline =$this->airlinerepo->create([
            'fshub_id' => '007',
            'icao' => 'AUA',
            'iata' => 'OS',
            'name' => 'Austrian Airlines',
            'callsign' => 'AUSTRIAN'
        ]);
            //Works with dd in the controller. At the moment no view is created yet.
        $this->get('airline')
            ->assertSee($airline->name)
            ->assertSee($airline->icao)
            ->assertSee($airline->iata)
            ->assertSee($airline->callsign);

    }

    /**@test*/
    function user_can_create_an_airline()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**@test*/
    function user_can_edit_an_airline()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}
