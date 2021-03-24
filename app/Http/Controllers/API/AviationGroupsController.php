<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Airline;
use Illuminate\Http\Request;

class AviationGroupsController extends Controller
{
    public function getAll() {
        $airlines = Airline::all();
        foreach($airlines as $a) {
            $a['name_combined'] = $a->icao.' - '.$a->name;
        }
        return response()->json($airlines);
    }
}
