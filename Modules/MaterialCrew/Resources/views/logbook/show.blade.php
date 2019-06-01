@extends('materialcrew::layouts.crewops')

@section('customcss')
    <script src="https://api.mqcdn.com/sdk/mapquest-js/v1.3.0/mapquest.js"></script>
    <link type="text/css" rel="stylesheet" href="https://api.mqcdn.com/sdk/mapquest-js/v1.3.0/mapquest.css"/>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col l6 s12">
                <div class="card grey darken-3 white-text">
                    <div class="card-content">
                        <h4>Crew</h4>
                        <div id="captain">
                            <ul class="collection with-header">
                                <li class="collection-item"><div>Username<div class="secondary-content">{{ $p->user->username }}</div></div></li>
                                <li class="collection-item"><div>Pilot ID<div class="secondary-content">{{ $p->user->pilotid }}</div></div></li>
                                <li class="collection-item"><div>Full Name<div class="secondary-content">{{ $p->user->first_name }} {{ $p->user->last_name }}</div></div></li>
                                <li class="collection-item"><div>Join Date<div class="secondary-content">{{ date('d/m/Y', strtotime($p->user->created_at)) }}</div></div></li>
                                <li class="collection-item"><div>Avg Landing Rate<div class="secondary-content">{{ \App\Models\Flight::where('user_id', $p->user->id)->avg('landingrate') }}</div></div></li>
                                <li class="collection-item"><div>Total Hours<div class="secondary-content">{{ \App\Models\Flight::where('user_id', $p->user->id)->sum('flighttime') }}</div></div></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col l6 s12">
                <div class="card grey darken-3 white-text">
                    <div class="card-content">
                        <h4>Flight Information</h4>
                        <ul class="tabs tabs-fixed-width">
                            <li class="tab"><a href="#basic">Basic Information</a></li>
                            @if($p->acars_client === "smartCARS")
                                <li class="tab"><a href="#sclogtab">smartCARS Logs</a></li>
                            @endif
                        </ul>
                        <div id="basic">
                            <ul class="collection with-header">
                                <li class="collection-item"><div>Airline<div class="secondary-content">{{ $p->airline->name }}</div></div></li>
                                <li class="collection-item"><div>Flight<div class="secondary-content">{{ $p->flightnum }}</div></div></li>
                                <li class="collection-item"><div>Departure<div class="secondary-content">{{ $p->depapt->icao }} {{ $p->depapt->name }}</div></div></li>
                                <li class="collection-item"><div>Arrival<div class="secondary-content">{{ $p->arrapt->icao }} {{ $p->arrapt->name }}</div></div></li>
                                <li class="collection-item"><div>Aircraft<div class="secondary-content">{{ $p->aircraft->name }} - {{ $p->aircraft->registration }}</div></div></li>
                                <li class="collection-item"><div>Distance Flown<div class="secondary-content">{{ $p->distance }}</div></div></li>
                                <li class="collection-item"><div>Fuel Used<div class="secondary-content">{{ $p->fuel_used }}</div></div></li>
                                <li class="collection-item"><div>Flight Time<div class="secondary-content">{{ $p->flighttime }}</div></div></li>
                                <li class="collection-item"><div>Landing Rate<div class="secondary-content">{{ $p->landingrate }}</div></div></li>
                                <li class="collection-item"><div>Aircraft<div class="secondary-content">{{ $p->aircraft->name }} - {{ $p->aircraft->registration }}</div></div></li>
                            </ul>
                        </div>
                        <div id="sclogtab">
                            @if($p->acars_client === "smartCARS")
                                <ul id="scLogs" class="collection with-header">

                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <pacx-flight-log url="{!! $p->pacx_url !!}" flight="{{$p->id}}"></pacx-flight-log>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            // Pull the data from the API so we can add stuff
            $.getJSON( "{{ config('app.url') }}api/v1/logbook/{{$p->id}}", function( data ) {
                console.log(data);
                // time to apply the flight status.
                switch(data.status) {
                    case 0:
                        $("#status").addClass("yellow darken-2");
                        $("#status-text").append('STATUS: <b>PENDING</b>');
                        break;
                    case 1:
                        $("#status").addClass("green");
                        $("#status-text").append('STATUS: <b>APPROVED</b>');
                        break;
                    case 2:
                        $("#status").addClass("red");
                        $("#status-text").append('STATUS: <b>DENIED</b>');
                        break;
                }
                if(data.acars_client === "smartCARS") {
                    if (data.sc_log !== null) {
                        var logSplit = data.sc_log.split("*");
                        $.each(logSplit, function (index, value) {
                            $("#scLogs").append('<li class="collection-item"><div>' + value + '</div></li>')
                        });
                    }
                }

            });
        });
    </script>
    <script>
        window.onload = function() {
            L.mapquest.key = 'xnnTtNLOeNr7ZLDolZszdbFcxPImHRbI';

            var map = L.mapquest.map('map', {
                center: [39.7392, -104.9903],
                layers: L.mapquest.tileLayer('map'),
                zoom: 3
            });
            $.getJSON( "{{ config('app.url') }}api/v1/logbook/{{$p->id}}", function( data ) {
                console.log(data);

                L.marker([data.depapt.lat, data.depapt.lon], {
                    icon: L.mapquest.icons.marker({
                        primaryColor: '#147f11',
                        secondaryColor: '#3b983e',
                        shadow: true,
                        size: 'md',
                        symbol: 'D'
                    }),
                    draggable: false
                }).bindPopup(data.depapt.name).addTo(map);

                L.marker([data.arrapt.lat, data.arrapt.lon], {
                    icon: L.mapquest.icons.marker({
                        primaryColor: '#7f0b0c',
                        secondaryColor: '#982e32',
                        shadow: true,
                        size: 'md',
                        symbol: 'A'
                    }),
                    draggable: false
                }).bindPopup(data.arrapt.name).addTo(map);

                var route = [];

                $.each(data.acarsdata, function (index, value) {
                    route.push([value.lat, value.lon])
                });


                L.polyline(route, {color: 'red'}).addTo(map);

            });

        };
    </script>
@endsection