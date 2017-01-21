<?php

namespace App\Http\Controllers\CrewOps;

use App\AircraftGroup;
use App\Airline;
use App\Bid;
use App\Models\Aircraft;
use App\Models\Airport;
use App\PIREP;
use App\ScheduleTemplate;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class CrewOpsController extends Controller
{
    public function index()
    {
        // Get the total number of bids for the user
        $totalbids = Bid::where('user_id', Auth::user()->id)->get();
        $totalLogs = PIREP::where('user_id', Auth::user()->id)->get();
        return view('crewops.dashboard', ['bids' => $totalbids, 'logs' => $totalLogs]);
    }
    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore(Auth::id()),
            ],
            'vatsim' => 'integer',
            'ivao' => 'integer',
            'password' => 'same:password2',
            'password2' => 'same:password',
        ]);

        $user = User::find(Auth::id());

        $user->email = $request->email;
        $user->vatsim = $request->vatsim;
        $user->ivao = $request->ivao;

        if(!empty($request->password)) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect('flightops/profile/' . Auth::id());
    }
    public function profileShow($id)
    {
        $user = User::findOrFail($id);

        $pireps = PIREP::where('user_id', $id)
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

        $query = array();

        // Check the request for specific info??
        if ($request->query('airline') != 0)
            $query['airline_id'] = $request->query('airline');

        if ($request->query('depapt') != 0)
            $query['depapt_id'] = $request->query('depapt');

        if ($request->query('arrapt') != 0)
            $query['arrapt_id'] = $request->query('arrapt');

        if ($request->query('aircraft') != 0)
            $query['aircraft_group_id'] = $request->query('aircraft');

        // Load all the schedules within the database
        if (empty($query)) {
            $schedules = ScheduleTemplate::with('depapt')->with('arrapt')->with('airline')->with('aircraft_group')->orderBy('airline_id', 'desc')->paginate(9);
            //dd($schedules);
        }
        else
            $schedules = ScheduleTemplate::where($query)->with('depapt')->with('arrapt')->with('airline')->with('aircraft_group')->orderBy('arrapt_id', 'desc')->paginate(9);
        $aircraft = Aircraft::all();
        //$schedules = ScheduleTemplate::all();
        //dd($schedules);
        // Return the view
        //return $schedules;
        return view('crewops.schedule.view', ['schedules' => $schedules, 'aircraft' => $aircraft]);
    }
    public function getScheduleAJAX(Request $request)
    {
        // Find out what we are searching for.
        $schedules = new ScheduleTemplate;
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
}
