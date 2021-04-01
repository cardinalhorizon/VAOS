<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AviationGroup;
use Illuminate\Http\Request;

class AviationGroupsController extends Controller
{
    public function getAll() {
        $airlines = AviationGroup::all();
        foreach($airlines as $a) {
            $a['name_combined'] = $a->icao.' - '.$a->name;
        }
        return response()->json($airlines);
    }
}
