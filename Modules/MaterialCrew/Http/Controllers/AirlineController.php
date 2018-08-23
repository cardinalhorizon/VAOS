<?php

namespace Modules\MaterialCrew\Http\Controllers;

use App\Models\Airline;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AirlineController extends Controller
{
    public function index()
    {
        $airlines = Airline::with('users')->get();

        $user = Auth::user();

        // Ok now figure out if the user logged in is in the airline. If so, throw his info up and center.

        foreach ($airlines as $airline) {
            // Check the relationship to see if the user has the above airline.

            if ($user->hasAirline($airline)) {
                $airline->inAirline = true;
            } else {
                $airline->inAirline = false;
            }
        }

        //return $airlines;
        return view('materialcrew::airlines.index', ['airlines' => $airlines]);
    }
    public function joinAirline($airline_id)
    {
        // Join the airline.
        $a = Airline::find($airline_id);
        $a->users()->attach(Auth::user(), [
            'status' => 1,
            'primary' => false,
            'admin' => 0,
            'pilot_id' => rand(100000,999999)
        ]);
    }
}
