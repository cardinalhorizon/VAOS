<?php

namespace App\Http\Controllers\CrewOps;

use App\Http\Controllers\Controller;
use App\Models\Aircraft;
use App\Models\AircraftGroup;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use App\Models\LogbookEntry as PIREP;
use App\Models\Schedule;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CrewOpsController extends Controller
{
    public function index()
    {
        // Get the total number of flights for the user
        $totalbids = Flight::where('user_id', Auth::user()->id)->get();
        $totalLogs = Flight::where(['user_id' => Auth::user()->id, 'status' => 10])->get();

        return view('crewops.dashboard', ['flights' => $totalbids, 'logs' => $totalLogs]);
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

        return view('crewops.profile.view', ['user' => $user, 'pireps' => $pireps]);
    }

    public function profileEdit()
    {
        // Check if the user is the right user. We don't want someone modifying other people's profile.
        $user = User::find(Auth::user()->id);

        return view('crewops.profile.edit', ['user' => $user]);
    }

    public function getSchedule(Request $request)
    {
        if ($request->query('individual') == 'true') {
            dd($request);
        } else {
            $query = [];

            // Check the request for specific info??
            if ($request->has('airline')) {
                $query['airline_id'] = Airline::where('icao', $request->query('airline'))->first()->id;
            }

            if ($request->has('depapt')) {
                $query['depapt_id'] = Airport::where('icao', $request->query('depapt'))->first()->id;
            }

            if ($request->has('arrapt')) {
                $query['arrapt_id'] = Airport::where('icao', $request->query('arrapt'))->first()->id;
            }

            if ($request->has('aircraft')) {
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
            }
            //return $schedules;
            return view('crewops.schedule.view', ['schedules' => $schedules, 'aircraft' => $aircraft]);
        }
    }

    public function getScheduleAJAX(Request $request)
    {
        // Find out what we are searching for.
        $schedules = new Schedule;
    }

    public function getLogbook()
    {
        $pireps = PIREP::where('user_id', Auth::user()->id)->with('airline')->with('depapt')->with('arrapt')->with('aircraft')->get();

        return view('crewops.logbook.view', ['pireps' => $pireps]);
    }

    public function getScheduleSearch()
    {
        $airports = Airport::all();
        $airlines = Airline::all();
        $aircraft = AircraftGroup::all();

        return view('crewops.schedule.search', ['airports' => $airports, 'airlines' => $airlines, 'aircraft' => $aircraft]);
    }

    public function getRoster()
    {
        $users = User::all();

        return view('crewops.roster.view', ['users' => $users]);
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
        $pirep = PIREP::where('id', $id)->with('airline')->with('depapt')->with('arrapt')->with('aircraft')->with('user')->first();

        return view('crewops.logbook.show', ['p' => $pirep]);
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

    // lz = leading zero
    public function lz($num)
    {
        return (strlen($num) < 2) ? "0{$num}" : $num;
    }
}
