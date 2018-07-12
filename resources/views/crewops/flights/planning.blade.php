@extends('layouts.crewops')
@section('customcss')
    <style>
        .info-block {
            text-align: center;
            color: white;
        }
        .info-block-title {
            font-size: 2.6rem;
        }
        .info-block-item {
            font-size: 3.7rem;
            font-weight: bold;
        }
        .tab>a {
            color: white/* #61c7ff */ !important;
            text-transform: none !important;
            font-size: 1.73rem !important;
        }
        .tabs {
            display: flex;
            justify-content: space-between;

        }
        .tab-panel {
            margin-top: 2rem;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col s12">
            <div class="card grey darken-3" style="background: url('{!! $flight->arrapt->banner_url !!}'), url('https://i.imgur.com/qOXmrci.png');     background-repeat: no-repeat;
                    background-position: center;
                    background-size: cover;">
                <div style="position: absolute; height: 100%; width: 100%; background-color: rgba(25,25,25,.75);z-index: 0"></div>
                <div class="card-content" style="display: flex;justify-content: space-between;position: relative; z-index: 5;">
                    <div class="info-block">
                        <div class="info-block-title">Departure Airport</div>
                        <div class="info-block-item">{{ $flight->depapt->icao }}</div>
                    </div>
                    <div class="info-block">
                        <div class="info-block-title">Departure</div>
                        <div class="info-block-item">{{ $flight->depapt->icao }}</div>
                    </div>
                    <div class="info-block">
                        <div class="info-block-title">Arrival</div>
                        <div class="info-block-item">{{ $flight->arrapt->icao }}</div>
                    </div>
                    <div class="info-block">
                        <div class="info-block-title">Aircraft</div>
                        <div class="info-block-item">{{ $flight->aircraft->icao }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col m5 s12 offset-m1">
            <div class="card-offset-title">Crew</div>
            <div class="card grey darken-2">
                <div class="card-content white-text">

                </div>
            </div>
        </div>
        <div class="col m5 s12">
            <div class="card-offset-title">Weather</div>
            <div class="card grey darken-2">
                <div class="card-content white-text">
                    <ul class="collection with-header">
                        <li class="collection-item"><div>Airline<div class="secondary-content">{{ $flight->airline->name }}</div></div></li>
                        <li class="collection-item"><div>Flight Number<div class="secondary-content">{{ $flight->flightnum }}</div></div></li>
                        <li class="collection-item"><div>Departure<div class="secondary-content">{{ $flight->depapt->name }}</div></div></li>
                        <li class="collection-item"><div>Arrival<div class="secondary-content">{{ $flight->arrapt->name }}</div></div></li>
                        <li class="collection-item"><div>Equipment Type<div class="secondary-content">{{ $flight->aircraft->icao }}</div></div></li>
                        <li class="collection-item"><div>Registration<div class="secondary-content">{{ $flight->aircraft->registration }}</div></div></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col m5 s12 offset-m1">
            <div class="card-offset-title">Files</div>
            <div class="card grey darken-2">
                <div class="card-content white-text">

                </div>
            </div>
        </div>
        <div class="col m5 s12">
            <div class="card-offset-title">Aircraft</div>
            <div class="card grey darken-2">
                <div class="card-content white-text">
                    <ul class="collection with-header">
                        <li class="collection-item"><div>Airline<div class="secondary-content">{{ $flight->airline->name }}</div></div></li>
                        <li class="collection-item"><div>Flight Number<div class="secondary-content">{{ $flight->flightnum }}</div></div></li>
                        <li class="collection-item"><div>Departure<div class="secondary-content">{{ $flight->depapt->name }}</div></div></li>
                        <li class="collection-item"><div>Arrival<div class="secondary-content">{{ $flight->arrapt->name }}</div></div></li>
                        <li class="collection-item"><div>Equipment Type<div class="secondary-content">{{ $flight->aircraft->icao }}</div></div></li>
                        <li class="collection-item"><div>Registration<div class="secondary-content">{{ $flight->aircraft->registration }}</div></div></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col m10 s12 offset-m1">
            <div class="card-offset-title">Comments</div>
            <div class="card grey darken-2">
                <div class="card-content white-text">

                </div>
            </div>
        </div>
    </div>
@endsection