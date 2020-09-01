<?php

namespace App\Http\Controllers\Admin;

use App\Models\Airline;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\AircraftGroup;
use App\Classes\VAOS_Schedule;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response\JSONResponse
     */
    public function index()
    {
        // Load all the schedules within the database
        $schedules = Schedule::with('depapt', 'arrapt', 'airline', 'aircraft_group', 'aircraft')->get();

        $output = groupArray($schedules, 'depapt', true, true);
        //return response()->json($output);
        //$schedules = Schedule::all();
        //dd($schedules);
        // Return the view
        // return response()->json($output);
        return view('admin.schedules.view', ['schedules' => json_encode($output)]);
        //return $schedules;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($agrp)
    {
        if ($agrp === 'all') {
            $airline = Airline::all();
        }
        else
        {
            $airline = Airline::with('aircraft_groups')->find($agrp);
        }
        $acfgroups = AircraftGroup::with('airline')->get();
        //return $acfgroups;
        return view('admin.schedules.create', ['acfgrps' => $airline->aircraft_groups]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store($agrp, Request $request)
    {
        // For now, just send the input to the controller.
        // dd($request);
        // Convert Request into Array
        $data = json_decode($request->input('data'));
        //dd($data);
        foreach ($data->routes as $route)
        {
            $route->airline = $data->airline;
            //dd($route);
            VAOS_Schedule::newRoute($route);
        }

        $request->session()->flash('schedule_created', true);

        return redirect()->route('admin.schedule.index', ['agrp' => $agrp]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('admin/schedule');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($agrp, $id)
    {
        $schedule = Schedule::with('airline','aircraft_group','aircraft','depapt','arrapt')->findOrFail($id);
        $airline = Airline::with('aircraft_groups')->find($agrp);
        foreach ($schedule->aircraft_group as $a) {
            if ($a['pivot']['primary']) {
                $schedule->primary_group = $a;
                break;
            }
        }
        return view('admin.schedules.edit', ['schedule' => $schedule, 'acfgrps' => $airline->aircraft_groups]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$agrp, $id)
    {
        $data = json_decode($request->input('data'), true);
        VAOS_Schedule::updateRoute($data, $id);

        $request->session()->flash('schedule_updated', true);

        return redirect()->route('admin.schedule.index', ['agrp' => $agrp]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($agrp, $id)
    {
        // Delete the route from the system
        Schedule::destroy($id);

        return redirect()->route('admin.schedule.index', ['agrp' => $agrp]);
    }
}
