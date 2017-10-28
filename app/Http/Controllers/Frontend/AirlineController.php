<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Airline;
use Illuminate\Http\Request;

class AirlineController extends Controller
{
    /**
     * Display a listing of airlines.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $airlines = Airline::all();

        //TODO: Add view to this function
        return view('airline.index', compact($airlines));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $data = $this->validateWith([
            'icao' => 'required|alpha|min:3|max:3|unique:airlines, icao',
            'fshub_id' => 'nullable',
            'iata' => 'required|alpha|min:2|max:2|unique:airlines, iata',
            'name' => 'required|max:255|unique:airlines, name',
            'logo' => 'nullable|mimes:jpeg,png',
            'widget' => 'nullable|mimes:jpeg,png',
            'callsign' => 'required|max:255|unique:airlines, callsign'

        ]);

        $airline = Airline::create($data);

        //TODO: Add view to this function
        return redirect()->route('airline.index')->with('success', 'Successfully created the new '. $airline->name . ' airline to the database');
    }

    /**
     * Display the specified airline.
     *
     * @param  \App\Models\Airline  $airline
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Airline  $airline
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Airline $airline)
    {
       $data = $this->validateWith([
            'icao' => "required|alpha|min:3|max:3|unique:airlines, icao, $airline->id",
            'fshub_id' => 'nullable',
            'iata' => "required|alpha|min:2|max:2|unique:airlines, iata, $airline->id",
            'name' => "required|max:255|unique:airlines, name, $airline->id",
            'logo' => 'nullable|mimes:jpeg,png',
            'widget' => 'nullable|mimes:jpeg,png',
            'callsign' => "required|max:255|unique:airlines, callsign, $airline->id"

        ]);

        $airline->update($data);

        //TODO: Add view to this function
        return redirect()->route('airline.index')->with('success', 'Successfully update the '. $airline->name . ' airline in the database');
    }

    /**
     * Remove the specified airline from storage.
     *
     * @param  \App\Models\Airline  $airline
     * @return \Illuminate\Http\Response
     */
    public function destroy(Airline $airline)
    {
        $airline->delete();

        //TODO: Add view to this function
        return redirect()->route('airline.index')->with('success', 'Successfully delete the '. $airline->name .' airline from the database');
    }
}
