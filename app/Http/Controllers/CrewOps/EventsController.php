<?php

namespace App\Http\Controllers\CrewOps;

use App\Models\AirlineEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    public function index()
    {
        // get all the events in the system that are published and are public status.
        $events = AirlineEvent::where(['status' => 1, 'access' => 2])->where(['owner_id' => Auth::user()->id])->get();

        return view('crewops.events.view', ['events' => $events]);
    }

    public function view($url_slug)
    {
        try {
            $event = AirlineEvent::firstOrFail('url_slug', $url_slug)->with('flights');
        } catch (Exception $e) {
            return view('errors.404');
        }

        return view('crewops.events.show', ['event', $event]);
    }

    public function create()
    {
        //
    }

    public function store()
    {
        //
    }
}
