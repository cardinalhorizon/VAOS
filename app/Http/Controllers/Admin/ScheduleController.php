<?php

namespace App\Http\Controllers\Admin;

use App\AircraftGroup;
use App\Airline;
use App\Classes\VAOS_Schedule;
use App\ScheduleTemplate;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Load all the schedules within the database
        $schedules = ScheduleTemplate::with('depapt')->with('arrapt')->with('airline')->with('aircraft_group')->get();

        //$schedules = ScheduleTemplate::all();
        //dd($schedules);
        // Return the view
        return view('admin.schedules.view', ['schedules' => $schedules]);
        //return $schedules;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $airlines = Airline::all();
        $acfgroups = AircraftGroup::all();
        return view('admin.schedules.create', ['airlines' => $airlines, 'acfgroups' => $acfgroups]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // For now, just send the input to the controller.
        // dd($request);
        // Convert Request into Array
        $data = $request->all();
        VAOS_Schedule::newRoute($data);
        return redirect('/admin/schedule');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
