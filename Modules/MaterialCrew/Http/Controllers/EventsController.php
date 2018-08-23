<?php

namespace Modules\MaterialCrew\Http\Controllers;

use Ramsey\Uuid\Uuid;
use App\Models\Flight;
use App\Models\AirlineEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    public function index()
    {
        // get all the events in the system that are published and are public status.
        $events = AirlineEvent::where(['status' => 1, 'access' => 2])->where(['owner_id' => Auth::user()->id])->get();

        return view('materialcrew::events.view', ['events' => $events]);
    }

    public function view($url_slug)
    {
        try {
            $event = AirlineEvent::firstOrFail('url_slug', $url_slug)->with('flights');
        } catch (Exception $e) {
            Log::error('Event "'.$url_slug.'" was not found.');

            return view('errors.404');
        }

        return view('materialcrew::events.show', ['event', $event]);
    }

    public function create()
    {
        //
    }

    public function createGroupFlight(Request $request)
    {
        $event                    = new AirlineEvent();
        $event->name              = "'s Group Flight";
        $event->url_slug          = Uuid::uuid1()->toString();
        $event->type              = 1;
        $event->access            = 1;
        $event->scope             = 1;
        $event->status            = 0;
        $event->ignoreTypeRatings = true;
        $event->publishToNetwork  = false;
        $event->save();

        if ($request->has('flights')) {
            // Pull that flight sequence
            $flights = Flight::find($request->input('flights'));
            //dd($request->input('flights'));
            $fa = [];
            $i  = 0;
            foreach ($flights as $flight) {
                // run it up
                $fa[$i]['isGroupFlight'] = true;
                $fa[$i]['depapt_id']     = $flight['depapt_id'];
                $fa[$i]['arrapt_id']     = $flight['arrapt_id'];
                if (! is_null($flight['route'])) {
                    $fa[$i]['route'] = $flight['route'];
                }
                $i++;
            }
            //dd($fa);
            $event->flights()->createMany($fa);
        }

        return redirect()->action('EventsController@view', ['slug' => $event->url_slug]);
    }

    public function store()
    {
        //
    }
}
