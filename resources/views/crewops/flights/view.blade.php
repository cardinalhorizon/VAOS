@extends('layouts.crewops')
@section('content')

    <div class="z-depth-2" style="position: relative; width: 100%; height: 300px; overflow: hidden; background: url('{{ Auth::user()->cover_url }}'), url(http://i.imgur.com/3UZDNCM.png);     background-repeat: no-repeat;
            background-position: center;
            background-size: cover;">
        <div style="height: 100%; background: linear-gradient(rgba(255,0,0,0), rgba(255,0,0,0), rgba(69,69,69,0.9))">
        </div>
        <h3 class="white-text" style="position: absolute; bottom: 0; left: 2rem;">My Flights</h3>
    </div>
    <div class="container" style="width: 90%">
        <div class="row">
            @foreach($flights as $s)
                <div class="col s12">
                    <div class="card">
                        <div class="card-image grey darken-3" style="height:150px; overflow: hidden;">
                            <!-- <img class="activator" src="https://raw.githubusercontent.com/CardinalHorizon/VAOS/master/public/img/login.png"> -->
                            <img style="width: 150px; height: 150px; position: absolute;" src="{{ $s->airline->widget }}">
                            <span style="bottom: -20px; font-size: 30px;z-index: 1;" class="card-title">{{ $s->airline->icao }}{{ $s->flightnum }}</span>
                        </div>

                        <div class="card-content">
                            <a style="position: absolute;right: 24px;bottom: 135px;" class="btn-floating activator waves-effect waves-light red"><i class="material-icons">more_vert</i></a>
                            <span style="display: inline-flex; vertical-align: middle;" class="card-title activator grey-text text-darken-4">{{ $s->depapt->icao }}<i class="material-icons">&#xE5C8;</i>{{ $s->arrapt->icao }}<i class="material-icons"></i></span>
                        </div>
                        <div class="card-reveal" style="z-index: 2;">
                            <span class="card-title grey-text text-darken-4">{{ $s->airline->icao }}{{ $s->flightnum }}<i class="material-icons right">close</i></span>
                            <ul class="collection with-header">
                                <li class="collection-item"><div>Aircraft Group<div class="secondary-content">@if($s->aircraft_group == null)
                                                Not Assigned
                                            @else
                                                {{$s->aircraft_group->name}}
                                            @endif</div></div></li>
                                <li class="collection-item"><div>Airline<div class="secondary-content">{{ $s->airline->name }}</div></div></li>
                            </ul>
                        </div>
                        <div class="card-action">
                            <a href="#" class="btn blue white-text" role="button">Details</a>
                            <a href="#" class="btn green white-text" role="button">Briefing</a>
                            <a href="#" class="btn red white-text" role="button" onclick="event.preventDefault();
                                    document.getElementById('delete-bid{{ $s->id }}').submit();">Cancel</a>
                            <form id="delete-bid{{ $s->id }}" method="POST" action="{{ url('flights'.$s->id) }}" accept-charset="UTF-8" hidden>
                                {{ csrf_field() }}
                                <input name="_method" type="hidden" value="DELETE">
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
