<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function createTrip(Request $request) {}
    public function startTrip($id, Request $request) {}
    public function endTrip($id) {}
    public function deleteTrip($id) {}
}
