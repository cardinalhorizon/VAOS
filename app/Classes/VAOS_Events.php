<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 1/2/18
 * Time: 3:46 AM
 */

namespace App\Classes;


use App\Models\AirlineEvent;

class VAOS_Events
{
    public static function createEvent($data)
    {
        $event = new AirlineEvent();

        $event->name = $data['name'];
        $event->description = $data['description'];

        if(!isNull($data['max_users'])) {
            $event->max_users = $data['max_users'];
        }
        if(!isNull($data['banner_url'])) {
            $event->max_users = $data['banner_url'];
        }
        $event->access = $data['access'];
        $event->scope = $data['scope'];
        $event->publishToNetwork = false;

        $event->save();
    }
    public static function joinEvent($event_id, $user)
    {
        $event = AirlineEvent::find($event_id);
        $event->users()->attach($user, ['status' => 1]);
    }
    public static function leaveEvent($event_id, $user)
    {
        $event = AirlineEvent::find($event_id);
        $event->users()->detach($user);
    }
    public static function modifyEvent($event, $data)
    {
        //
    }
    public static function removeEvent($event_id)
    {
        $event = AirlineEvent::find($event_id);
        $event->delete();
    }
    public static function dispatchFlights($event_id, $user_id = null)
    {
        // TODO: Add event flights to bids. Optional: Individual add bids to flights. Especially when dealing with a larger event.

    }
    public static function addEventFlight($event_id, $flightInfo)
    {
        //
    }
    public static function removeEventFlight($event_id, $flight_id)
    {
        //
    }
    public static function modifyEventFlight($devent_id, $flightInfo)
    {
        //
    }
}