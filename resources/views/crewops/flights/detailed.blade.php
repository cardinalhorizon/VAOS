@extends('layouts.crewops')
@section('customcss')
    <script src="https://api.mqcdn.com/sdk/mapquest-js/v1.3.0/mapquest.js"></script>
    <link type="text/css" rel="stylesheet" href="https://api.mqcdn.com/sdk/mapquest-js/v1.3.0/mapquest.css"/>
@endsection
@section('content')
    <div class="z-depth-2" style="position: relative; width: 100%; height: 300px; overflow: hidden; background: url('{{ Auth::user()->cover_url }}'), url(http://i.imgur.com/3UZDNCM.png);     background-repeat: no-repeat;
            background-position: center;
            background-size: cover;">
        <div style="height: 100%; background: linear-gradient(rgba(255,0,0,0), rgba(255,0,0,0), rgba(69,69,69,0.9))">
        </div>
        <h3 class="white-text" style="position: absolute; bottom: 0; left: 2rem;">{{ $flight->airline->icao }}{{ $flight->flightnum }} Briefing</h3>
    </div>
    <div class="container">
        <div class="row">
            <div class="col l3 s12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Flight Details</span>
                        <ul class="collection with-header">
                            <li class="collection-item"><div>Airline<div class="secondary-content">{{ $flight->airline->name }}</div></div></li>
                            <li class="collection-item"><div>Flight Number<div class="secondary-content">{{ $flight->flightnum }}</div></div></li>
                            <li class="collection-item"><div>Departure<div class="secondary-content">{{ $flight->depapt->icao }}</div></div></li>
                            <li class="collection-item"><div>Arrival<div class="secondary-content">{{ $flight->arrapt->icao }}</div></div></li>
                            <li class="collection-item"><div>Equipment Type<div class="secondary-content">{{ $flight->aircraft->icao }}</div></div></li>
                            <li class="collection-item"><div>Registration<div class="secondary-content">{{ $flight->aircraft->registration }}</div></div></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col l6 s12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Flight Details</span>
                        <div id="map" style="width: auto; height: 40vh;"></div>
                    </div>
                </div>
            </div>
            <div class="col l3 s12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Current Flight Status</span>
                        <ul class="collection with-header">
                            <li class="collection-item"><div>Latitude<div class="secondary-content">{{ $flight->lat }}</div></div></li>
                            <li class="collection-item"><div>Longitude<div class="secondary-content">{{ $flight->lon }}</div></div></li>
                            <li class="collection-item"><div>Ground Speed<div class="secondary-content">{{ $flight->gs }}</div></div></li>
                            <li class="collection-item"><div>Altitude<div class="secondary-content">{{ $flight->altitude }}</div></div></li>
                            <li class="collection-item"><div>Network<div class="secondary-content">{{ $flight->network }}</div></div></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        window.onload = function() {
            L.mapquest.key = 'xnnTtNLOeNr7ZLDolZszdbFcxPImHRbI';

            var map = L.mapquest.map('map', {
                center: [39.7392, -104.9903],
                layers: L.mapquest.tileLayer('map'),
                zoom: 3
            });
            $.getJSON( "{{ config('app.url') }}api/v1/flights/{{$flight->id}}", function( data ) {
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

                if(data.lat !== null) {
                    L.marker([data.lat, data.lon], {
                        icon: L.mapquest.icons.circle({
                            primaryColor: '#22407F',
                            secondaryColor: '#3B5998',
                            shadow: true,
                            size: 'sm'
                        }),
                        draggable: false
                    }).addTo(map);
                }

                var route = [
                    [data.depapt.lat, data.depapt.lon],
                    [data.arrapt.lat, data.arrapt.lon]
                ];

                L.polyline(route, {color: 'black'}).addTo(map);

            });

        };
    </script>
@endsection