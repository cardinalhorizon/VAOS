@extends('layouts.crewops')
@section('content')
    <div class="z-depth-2" style="position: relative; width: 100%; height: 300px; overflow: hidden; background: url('{{ Auth::user()->cover_url }}'), url(http://i.imgur.com/3UZDNCM.png);     background-repeat: no-repeat;
            background-position: center;
            background-size: cover;">
        <div style="height: 100%; background: linear-gradient(rgba(255,0,0,0), rgba(255,0,0,0), rgba(69,69,69,0.9))">
        </div>
        <h3 class="white-text" style="position: absolute; bottom: 0; left: 2rem;">{{ $bid->airline->icao }}{{ $bid->flightnum }} Briefing</h3>
    </div>
    <div class="row">
        <div class="col l4 s12">
            <div class="card"></div>
        </div>
    </div>
@endsection