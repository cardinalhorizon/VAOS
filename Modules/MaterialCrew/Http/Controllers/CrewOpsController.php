<?php

namespace Modules\MaterialCrew\Http\Controllers;

use App\Classes\VAOS_Airports;
use App\User;
use App\Models\Flight;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Aircraft;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\AircraftGroup;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\LogbookEntry as PIREP;

class CrewOpsController extends Controller
{
    public function index()
    {
        // Get the total number of flights for the user
        $totalbids = Flight::where('user_id', Auth::user()->id)->get();
        $totalLogs = Flight::where(['user_id' => Auth::user()->id, 'status' => 10])->get();

        return view('materialcrew::dashboard', ['flights' => $totalbids, 'logs' => $totalLogs]);
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore(Auth::id()),
            ],
            'vatsim'    => 'integer',
            'ivao'      => 'integer',
            'password'  => 'same:password2',
            'password2' => 'same:password',
        ]);

        $user = User::find(Auth::id());

        $user->email  = $request->email;
        $user->vatsim = $request->vatsim;
        $user->ivao   = $request->ivao;

        if (! empty($request->password)) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect('flightops/profile/'.Auth::id());
    }

    public function profileShow($id)
    {
        $user = User::findOrFail($id);

        $pireps = Flight::where(['user_id' => $id, 'status' => 10])
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('materialcrew::profile.view', ['user' => $user, 'pireps' => $pireps]);
    }

    public function profileEdit()
    {
        // Check if the user is the right user. We don't want someone modifying other people's profile.
        $user = User::find(Auth::user()->id);

        return view('materialcrew::profile.edit', ['user' => $user]);
    }

    public function getSchedule(Request $request)
    {
        if ($request->query('individual') == 'true') {
            dd($request);
        } else {
            $query = [];

            // Check the request for specific info??
            if ($request->has('airline') && $request->query('airline') !== '') {
                $query['airline_id'] = Airline::where('icao', $request->query('airline'))->first()->id;
            }

            if ($request->has('depapt') && $request->query('depapt') !== '') {
                $query['depapt_id'] = Airport::where('icao', $request->query('depapt'))->first()->id;
            }

            if ($request->has('arrapt') && $request->query('arrapt') !== '') {
                $query['arrapt_id'] = Airport::where('icao', $request->query('arrapt'))->first()->id;
            }

            if ($request->has('aircraft') && $request->query('aircraft') !== '') {
                $query['aircraft_group_id'] = AircraftGroup::where('icao', $request->query('aircraft'))->first()->id;
            }
            //dd($query);
            // Load all the schedules within the database
            if (empty($query)) {
                $schedules = Schedule::with('depapt')->with('arrapt')->with('airline')->with('aircraft_group')->orderBy('airline_id', 'desc')->paginate(8);
            //dd($schedules);
            } else {
                $schedules = Schedule::where($query)->with('depapt')->with('arrapt')->with('airline')->with('aircraft_group')->orderBy('arrapt_id', 'desc')->paginate(8);
            }
            $aircraft = Aircraft::all();
            //$schedules = Schedule::all();
            //dd($schedules);
            // Return the view
            foreach ($schedules as $s) {
                $s->primary_aircraft = null;
                $s->inAirline        = true;
                // Check if member of airline.
                if (! Auth::user()->hasAirline($s->airline->id)) {
                    $s->inAirline = false;
                }
                if (is_null($s->deptime)) {
                    $s->deptime = 'N/A';
                }
                if (is_null($s->arrtime)) {
                    $s->arrtime = 'N/A';
                }
                foreach ($s->aircraft_group as $a) {
                    if ($a['pivot']['primary']) {
                        $s->primary_aircraft = $a->icao;
                    }
                }
                if (!isset($s->depapt)) {
                    VAOS_Airports::checkOrAdd(intval($s->depapt_id));
                }
                if (!isset($s->arrapt)) {
                    VAOS_Airports::checkOrAdd(intval($s->arrapt_id));
                }
            }
            //return $schedules;
            return view('materialcrew::schedule.view', ['schedules' => $schedules, 'aircraft' => $aircraft]);
        }
    }

    public function getScheduleAJAX(Request $request)
    {
        // Find out what we are searching for.
        $schedules = new Schedule;
    }

    public function getLogbook()
    {
        $pireps = Flight::with('airline', 'depapt', 'arrapt', 'aircraft', 'user', 'fo')->where('user_id', Auth::user()->id)->completed();

        return view('materialcrew::logbook.view', ['pireps' => $pireps]);
    }

    public function getScheduleSearch()
    {
        $airports = Airport::all();
        $airlines = Airline::all();
        $aircraft = AircraftGroup::all();

        return view('materialcrew::schedule.search', ['airports' => $airports, 'airlines' => $airlines, 'aircraft' => $aircraft]);
    }

    public function getRoster()
    {
        $users = User::all();

        return view('materialcrew::roster.view', ['users' => $users]);
    }

    public function postManualPirep(Request $request)
    {
        $flightinfo = Flight::find($request->bid);
        $pirep      = new PIREP();
        $pirep->user()->associate(Auth::user()->id);
        $pirep->airline()->associate($flightinfo->airline_id);
        $pirep->aircraft()->associate($flightinfo->aircraft_id);
        $pirep->depapt()->associate($flightinfo->depapt_id);
        $pirep->arrapt()->associate($flightinfo->arrapt_id);
        $pirep->flightnum    = $flightinfo->flightnum;
        $pirep->flighttime   = 0;
        $pirep->distance     = 0;
        $pirep->acars_client = 'manual';
        $pirep->route        = 'Manually Filed';
        $pirep->status       = 0;
        $pirep->landingrate  = $request->input('landingrate');

        $pirep->save();
        $flightinfo->delete();

        $request->session()->flash('success', 'Manual PIREP submitted for manual approval.');

        return redirect('/flightops');
    }

    public function getLogbookDetailed($id)
    {
        $pirep = Flight::with('airline', 'depapt', 'arrapt', 'aircraft', 'user', 'fo')->find($id);

        return view('materialcrew::logbook.show', ['p' => $pirep]);
    }

    public function convertTime($dec)
    {
        // start by converting to seconds
        $seconds = ($dec * 3600);
        // we're given hours, so let's get those the easy way
        $hours = floor($dec);
        // since we've "calculated" hours, let's remove them from the seconds variable
        $seconds -= $hours * 3600;
        // calculate minutes left
        $minutes = floor($seconds / 60);
        // remove those from seconds as well
        $seconds -= $minutes * 60;
        // return the time formatted HH:MM:SS
        //return lz($hours).":".lz($minutes).":".lz($seconds);
        return $hours;
    }

    public function addAircraft(Request $request)
    {
        $acf  = new Aircraft();
        $data = $request->all();
        //try
        $acf->icao         = $data['icao'];
        $acf->name         = $data['name'];
        $acf->manufacturer = $data['manufacturer'];
        $acf->registration = $data['registration'];
        $acf->status       = 1;
        $acf->user()->associate(Auth::user());

        $acf->save();

        return redirect(route('flightops.profile', ['id' => Auth::user()->id]));
    }

    // lz = leading zero
    public function lz($num)
    {
        return (strlen($num) < 2) ? "0{$num}" : $num;
    }
    public function storePacx(Request $request) {
        $data = json_decode($request->input('data'));
        //dd($data);
        $flight = Flight::find(intval($data->flight));
        $flight->pacx_url = $data->report_url;

        $flight->save();

        return redirect()->action('\Modules\MaterialCrew\Http\Controllers\CrewOpsController@getLogbookDetailed', ['id' => $flight->id]);
    }
}
