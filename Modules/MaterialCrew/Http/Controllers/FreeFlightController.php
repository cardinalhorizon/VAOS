<?php

namespace Modules\MaterialCrew\Http\Controllers;

use App\Models\Flight;
use App\Models\Aircraft;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Classes\VAOS_Airports as APC;

class FreeFlightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('materialcrew::index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('materialcrew::freeflight');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //dd($request);
        // Check if the airports are added. If not, we need to add them.
        $dep = APC::checkOrAdd($request->input('depicao'));
        $arr = APC::checkOrAdd($request->input('arricao'));
        $acf = Aircraft::find($request->input('aircraft'));
        // Create the flight
        $flight = new Flight();
        if(isEmpty($request->input('callsign')))
        {
            $flight->callsign = $acf->registration;
        }
        else
        {
            $flight->callsign = $request->input('callsign');
        }

        $flight->depapt()->associate($dep);
        $flight->arrapt()->associate($arr);
        $flight->user()->associate(Auth::user()->id);
        $flight->aircraft()->associate($acf->id);

        $flight->state = 0;

        $flight->save();

        return action('BiddingController@show', ['id' => $flight->id]);
    }

    /**
     * Show the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return view('materialcrew::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit()
    {
        return view('materialcrew::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy()
    {
    }
}
