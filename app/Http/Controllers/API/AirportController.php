<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function searchAirports(Request $request) {
        if (is_null($request->query('keyword')))
            return response()->json([]);
        $airports = Airport::where('name', 'like', "%{$request->query('keyword')}%")->orWhere('icao', 'like', "%{$request->query('keyword')}%")->limit(20)->get();
        foreach($airports as $a) {
            $a['name_combined'] = $a->icao.' - '.$a->name;
        }
        return response()->json($airports);
    }
}
