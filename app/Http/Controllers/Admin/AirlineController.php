<?php

namespace App\Http\Controllers\Admin;

use App\Airline;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AirlineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Return the list of airlines

        $airlines = Airline::all();
        if ($airlines->count() == 0)
            return redirect('/admin/airlines/create');
        return view('admin.airline.view', ['airlines' => $airlines]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.airline.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $airline = new Airline();

        $airline->name = $request->input('name');
        $airline->icao = $request->input('icao');
        if ($request->input('iata') != null)
            $airline->iata = $request->input('iata');
        $airline->callsign = $request->input('callsign');
        if ($request->input('logo') != null)
            $airline->logo = $request->input('logo');
        if ($request->input('widget') != null)
            $airline->widget = $request->input('widget');

        $airline->save();

        $request->session()->flash('airline_created', true);
        return redirect('/admin/airlines');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('admin/airlines');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $airline = Airline::findOrFail($id);

        return view('admin.airline.edit', ['airline' => $airline]);
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
        $airline = Airline::find($id);

        $airline->name = $request->input('name');
        $airline->icao = $request->input('icao');
        if ($request->input('iata') != null)
            $airline->iata = $request->input('iata');
        $airline->callsign = $request->input('callsign');
        if ($request->input('logo') != null)
            $airline->logo = $request->input('logo');
        if ($request->input('widget') != null)
            $airline->widget = $request->input('widget');

        $airline->save();

        $request->session()->flash('updated', true);
        return redirect('/admin/airlines');
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
