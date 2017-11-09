<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Airline;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAirlineRequest;
use App\Http\Requests\UpdateAirlineRequest;

class AirlineController extends Controller
{
    private $airlineService;

    public function __construct()
    {
        $this->airlineService = app('App\Services\AirlineService');
    }

    /**
     * Display a listing of airlines.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $airlines = $this->airlineService->index();

        //TODO: Add view to this function
        return view('airline.index', compact('airlines'));
    }

    /**
     * Show the form for creating a new airline.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //TODO: Add view to this function
        return view('airline.create');
    }

    /**
     * Store a newly created airline in storage.
     *
     * @param StoreAirlineRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAirlineRequest $request)
    {
        $airline = $this->airlineService->create($request);

        //TODO: Add view to this function
        return redirect()->route('airline.index')->with('success', 'Successfully created the new '.$airline->name.' airline to the database');
    }

    /**
     * Display the specified airline.
     *
     * @param  \App\Models\Airline  $airline
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Airline $airline)
    {
        //TODO: Add view to this function
        return redirect()->route('airline.index');
    }

    /**
     * Show the form for editing the specified airline.
     *
     * @param  \App\Models\Airline  $airline
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Airline $airline)
    {
        //TODO: Add view to this function
        return view('airline.edit', compact($airline));
    }

    /**
     * Update the specified airline in storage.
     *
     * @param UpdateAirlineRequest $request
     * @param  \App\Models\Airline $airline
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAirlineRequest $request, Airline $airline)
    {
        $airline = $this->airlineService->update($request, $airline);

        //TODO: Add view to this function
        return redirect()->route('airline.index')->with('success', 'Successfully update the '.$airline->name.' airline in the database');
    }

    /**
     * Remove the specified airline from storage.
     *
     * @param  \App\Models\Airline $airline
     *
     * @return void
     */
    public function destroy(Airline $airline)
    {
        //
    }
}
