<?php

namespace App\Http\Controllers\CrewOps;

use App\AircraftGroup;
use App\Airline;
use App\Bid;
use App\Models\Aircraft;
use App\Models\Airport;
use App\PIREP;
use App\ScheduleTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CrewOpsController extends Controller
{
    public function index()
    {
        // Get the total number of bids for the user
        $totalbids = Bid::where('user_id', Auth::user()->id)->get();
        $totalLogs = PIREP::where('user_id', Auth::user()->id)->get();
        return view('crewops.dashboard', ['bids' => $totalbids, 'logs' => $totalLogs]);
    }
    public function profileShow($id)
    {

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
}
