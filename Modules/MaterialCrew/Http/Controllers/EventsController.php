<?php

namespace Modules\MaterialCrew\Http\Controllers;

use Ramsey\Uuid\Uuid;
use App\Models\AirlineEvent;
use Illuminate\Http\Request;
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

    public function createGroupFlight(Request $request)
    {
        $event           = new AirlineEvent();
        $event->name     = Auth::user()->username."'s Group Flight";
        $event->url_slug = Uuid::uuid1()->toString();
        $event->type     = 1;
        $event->access   = 1;
        $event->scope    = 1;
        $event->save();

        if ($request->has('flight_sequence')) {
            // Pull that flight sequence
        }

        // Add the flights
        if (is_array($flights)) {
            // Ok it's an array, we need to add all the fights into the array from here.
        }
    }

    public function store()
    {
        //
    }
}
