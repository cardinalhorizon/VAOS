<?php

namespace App\Console\Commands;

use App\Classes\VAOS_Aircraft;
use App\Classes\VAOS_Airline;
use App\Classes\VAOS_Schedule;
use App\Models\AircraftGroup;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\ExtHour;
use App\Models\Hub;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportPhpVMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vaos:importphpVMS';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import what can be imported from phpVMS Database into VAOS';

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
        $this->info('Loading System Files');
        $vms_airlines = collect(json_decode(file_get_contents('airlines.json')));
        $vms_aircraft = collect(json_decode(file_get_contents('aircraft.json')));
        $vms_users = collect(json_decode(file_get_contents('users.json')));
        $vms_schedule = collect(json_decode(file_get_contents('schedule.json')));

        $new_aircraft = [];
        // First, Assign all the aircraft airlines.
        // To do this, we will look at the schedule table for the assignment.
        foreach ($vms_aircraft as $a)
        {
            try
            {
                $a->airline = null;
                foreach($vms_schedule as $s)
                {
                    if ($s->aircraft === $a->id)
                    {
                        // We found the first match. It's all we need. Assign it to an airline code
                        $a->airline = $s->code;
                        array_push($new_aircraft, $a);
                        //$this->info($a->registration);
                        break;
                    }
                }
            }
            catch (\Exception $e)
            {
                $this->warn('Cannot Find Airline for '.$a->registration);
            }
        }
        // Now that we have the required data, time to create everything.
        // First, let's start with the airlines.
        $this->warn('Starting Import');
        $this->warn('===============');
        foreach ($vms_airlines as $a)
        {
            $airline = Airline::create([
                'name' => $a->name,
                'icao' => $a->code,
                'callsign' => $a->name
            ]);
            //$this->info($airline->name.' Created');
        }

        // Airlines Complete. Time for Aircraft!!! FUCK ME...
        $this->warn('Total Aircraft Importing: '.count($new_aircraft));
        $i = 0;
        foreach($new_aircraft as $a)
        {
            $data = [
                'icao' => $a->icao,
                'registration' => $a->registration,
                'status' => $a->enabled,
                'name' => $a->name,
                'manufacturer' => 'N/A'
            ];

            $data['airline']= $a->airline;


            VAOS_Aircraft::createAircraft($data, true);

            $this->info($a->registration.' Added');
            $i++;
        }
        $this->warn('Total Imported: '.$i);
        // Ok now for the schedule!

        $this->warn('Importing Schedule');
        $this->warn('==================');
        foreach($vms_schedule as $s)
        {
            // Get the airline object
            $airline = Airline::where('icao', $s->code)->first();
            // Find the aircraft group based on the aircraft code, then assign it.
            foreach ($vms_aircraft as $a)
            {
                if ($a->id === $s->aircraft)
                {
                    // Found it, grab the type code and reference the aircraft group.
                    $acfGrp = DB::table('aircraft_groups')->where([
                        ['icao', '=', $a->icao],
                        ['userdefined', '=', 'false'],
                        ['airline_id', '=', $airline->id],
                    ])->first();
                }
            }
            $data = new \stdClass();
            $data->airline = $airline;
            $data->flightnum = $s->flightnum;
            $data->depapt = $s->depicao;
            $data->arrapt = $s->arricao;
            $data->callsign = $s->code.$s->flightnum;
            $data->primary_group = $acfGrp;
            $data->status = $s->enabled;
            $data->aircraft_groups = [];

            $entry = VAOS_Schedule::newRoute($data);
            $this->info('Route Created: '.$entry->callsign.' | '.$entry->depapt->icao.'->'.$entry->arrapt->icao.' | Primary Aircraft Group: '.$acfGrp->icao);
        }
        // Generate the hubs based on the users.
        foreach ($vms_users as $a)
        {

            // First, query the airport.
            $apt = Airport::where('icao', $a->hub)->first();
            $airline = Airline::where('icao', $a->code)->first();
            // Check if we have the hub.
            if ($apt !== null) {
                if (DB::table('hubs')->where([['airport_id', '=', $apt['id']], ['airline_id', '=', $airline['id']]])->first() === null) {
                    try {


                        Hub::create([
                            'airport_id' => $apt->id,
                            'airline_id' => $airline->id
                        ]);
                        $this->info('Hub Created: ' . $airline->icao . ' at ' . $apt->icao);
                    } catch (\Exception $e) {
                        dd($apt, $airline, $a);
                    }
                }
            }
        }
        // Time for the users!!! The most important part.
        foreach ($vms_users as $d)
        {
            $pw = self::ranpw();
            $user = User::create([
                'first_name' => $d->firstname,
                'last_name'  => $d->lastname,
                'email'      => $d->email,
                'password'   => bcrypt($pw),
                'username'   => $d->code.$d->pilotid,
                'status'     => 1,
                'admin'      => false,
            ]);
            $ext_vms = new ExtHour();

            $ext_vms->user()->associate($user);
            $ext_vms->name = config('app.name').' phpVMS Hours';
            $ext_vms->total = $d->totalhours;
            $ext_vms->source_url = 'N/A';
            $ext_vms->approved = true;
            $ext_vms->save();

            $ext_trans = new ExtHour();

            $ext_trans->user()->associate($user);
            $ext_trans->name = 'Pre-'.config('app.name').' Transfer Hours';
            $ext_trans->total = $d->transferhours;
            $ext_trans->source_url = 'N/A';
            $ext_trans->approved = true;
            $ext_trans->save();

            // Join the airline.
            $apt = Airport::where('icao', $a->hub)->first();
            $airline = Airline::where('icao', $a->code)->first();
            // Check if we have the hub.

            $hub = DB::table('hubs')->where([
                    ['airport_id', '=', $apt->id],
                    ['airline_id', '=', $airline->id]
                ])->first();

            $airline->users()->attach($user, [
                'status'   => 1,
                'primary'  => true,
                'hub_id' => $hub->id,
                'admin'    => 0,
                'pilot_id' => $d->pilotid,
            ]);

            $this->info('User Created: '.$user->first_name.' '.$user->last_name.' | Assigned Hub: '.$apt->icao. ' | PID: '.$d->pilotid);
        }
        return true;
    }
    private function ranpw() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
