<?php

namespace App\Http\Controllers\CrewOps;

use App\Models\Airline;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AirlineController extends Controller
{
    public function index()
    {
        $airlines = Airline::with('users')->get();

        $user = Auth::user();

        // Ok now figure out if the user logged in is in the airline. If so, throw his info up and center.

        foreach ($airlines as $airline)
        {
            // Check the relationship to see if the user has the above airline.

            if ($user->hasAirline($airline))
            {
                $airline->inAirline = true;
            }
            else
            {
                $airline->inAirline = false;
            }

        }

        //return $airlines;
        return view('crewops.airlines.index', ['airlines' => $airlines]);
    }
}
