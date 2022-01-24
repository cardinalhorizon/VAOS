<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FlightsController extends Controller
{
    public function index() {

    }
    public function getFlight($id) {}
    public function getFlights(Request $request) {}
    public function searchFlights(Request $request) {}
    public function createFlight(Request $request) {
        //
    }
    public function modifyFlight(Request $request) {}
    public function positionReport(Request $request) {}
    public function startFlight($id) {
        //
    }
    public function endFlight($id) {}
}
