<?php

namespace App\Http\Controllers\Admin;

use App\Classes\AircraftData;
use App\Models\Aircraft;
use Illuminate\Http\Request;
use App\Airline;
use App\AircraftGroup;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FleetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fleet = Aircraft::with('hub')->with('location')->with('airline')->get();
        //return $fleet;
        return view('admin.fleet.view', ['fleet' => $fleet]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $airlines = Airline::all();
        $acfgroups = AircraftGroup::where('userdefined', true)->get();
        return view('admin.fleet.create', ['airlines' => $airlines, 'acfgroups' => $acfgroups]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = array();
        $data['icao'] = $request->input('icao');
        $data['name'] = $request->input('name');
        $data['manufacturer'] = $request->input('manufacturer');
        $data['registration'] = $request->input('registration');
        $data['range'] = $request->input('range');
        $data['maxpax'] = $request->input('maxpax');
        $data['maxgw'] = $request->input('maxgw');
        $data['enabled'] = $request->input('enabled');
        $data['airline'] = $request->input('airline');
        $data['hub'] = null;
        $data['group'] = null;
        //dd($data);

        if (AircraftData::createAircraft($data))
            return redirect('/admin/fleet/')->with(['success' => true]);
        else
            dd($data);
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
