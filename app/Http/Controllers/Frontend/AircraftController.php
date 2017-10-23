<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Aircraft;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AircraftController extends Controller
{
    /**
     * Display a listing of the fleet.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fleet = Aircraft::with('hub')->with('location')->with('airline')->get();

        //TODO: Add view to this function
        return view('aircraft.index', compact($fleet));
    }

    /**
     * Show the form for creating a new aircraft.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created aircraft in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified aircraft.
     *
     * @param  \App\Models\Aircraft  $aircraft
     * @return \Illuminate\Http\Response
     */
    public function show(Aircraft $aircraft)
    {
        //
    }

    /**
     * Show the form for editing the specified aircraft.
     *
     * @param  \App\Models\Aircraft  $aircraft
     * @return \Illuminate\Http\Response
     */
    public function edit(Aircraft $aircraft)
    {
        //
    }

    /**
     * Update the specified aircraft in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Aircraft  $aircraft
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Aircraft $aircraft)
    {
        //
    }

    /**
     * Remove the specified aircraft from storage.
     *
     * @param  \App\Models\Aircraft  $aircraft
     * @return \Illuminate\Http\Response
     */
    public function destroy(Aircraft $aircraft)
    {
        //
    }
}
