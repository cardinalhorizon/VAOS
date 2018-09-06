<?php

namespace Modules\MaterialCrew\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;
use App\Classes\VAOS_Schedule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BiddingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $flights = Flight::where('user_id', Auth::user()->id)->with('user', 'airline', 'depapt', 'arrapt', 'aircraft')->filed()->get();

        foreach ($flights as $f) {
            if (! is_null($f['airline_id'])) {
                $f->flight = $f->airline->icao.$f->flightnum;
            } else {
                $f->flight = $f->flightnum;
            }
        }
        //return $flights;
        return view('materialcrew::flights.view', ['flights' => $flights]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // file the bid within the system.
        if ($request->input('aircraft_id') === null) {
            // The schedule filed has a aircraft group assigned. Let the system handle it.
            $flight = VAOS_Schedule::fileBid(Auth::user()->id, $request->input('schedule_id'));
        } else {
            // No group assignment. This means an aircraft nees to be provided. Otherwise it will fail.
            $flight = VAOS_Schedule::fileBid(Auth::user()->id, $request->input('schedule_id'), $request->input('aircraft_id'));
        }

        if ($flight) {
            return redirect('/flightops');
        }
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
        $flight = Flight::with('user', 'fo')->with('airline')->with('depapt')->with('arrapt')->with('aircraft')->find($id);

        return view('materialcrew::flights.planning', ['flight' => $flight]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
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
     *
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
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        VAOS_Schedule::deleteBid($id, Auth::user()->id);

        return redirect('/flightops/flights');
    }
}
