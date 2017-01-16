<?php

namespace App\Console\Commands;

use App\Airline;
use Illuminate\Console\Command;

class VAOSNewAirline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vaos:newAirline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a New Airline instance within the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->ask('Airline Name:');
        $icao = $this->ask('ICAO:');
        $callsign = $this->ask('Callsign:');

        $airline = new Airline([
            'name' => $name,
            'icao' => $icao,
            'callsign' => $callsign
        ]);
        $airline->save();
        $this->info('Airline Created');
    }
}
