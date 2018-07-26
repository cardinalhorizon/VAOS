@extends('layouts.crewops')
@section('content')
    <div class="container" style="width: 90%">
        <div class="row">
            @foreach($flights as $s)
                <div class="col xl6 l12 m12 s12">
                    <a class="text-white modal-trigger" style="color: white;" href="#modal{{$s->id}}">
                        <div class="card hoverable" style="height: 175px; background: url('{!! $s->arrapt->banner_url !!}') black no-repeat center; background-size: cover; border-right: #6aff9a 20px solid; border-radius: 2px 5px 5px 2px">
                            <div style="position: absolute; height: 100%; width: 100%; background-color: rgba(25,25,25,.65);z-index: 0"></div>
                            <div class="card-content" style="position: relative; z-index: 5;height:175px;display:block;">
                                <div style="font-size: 2.4rem; font-weight: bold; line-height: 3rem;">{{ $s->airline->icao }}{{ $s->flightnum }}</div>
                                <div style="color: #ddd; font-size: 1.4rem; position: relative; margin-top: 65px; bottom: 0; width: 100%; font-weight: normal; display: flex;justify-content: space-between;">
                                    <span><i class="material-icons" style="font-size: 1.4rem;">flight_takeoff</i> {{ $s->depapt->icao }} | {{ $s->deptime }}</span>
                                    <span><i class="material-icons" style="font-size: 1.4rem;">flight_land</i> {{ $s->arrapt->icao }} | {{ $s->arrtime }}</span>
                                    <span><i class="material-icons" style="font-size: 1.4rem;">flight</i> {{ $s->aircraft->icao }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div id="modal{{$s->id}}" class="modal">
                    <div class="modal-content">
                        <h4>{{ $s->airline->icao }}{{ $s->flightnum }}</h4>
                        <p>A bunch of text</p>
                    </div>
                    <div class="modal-footer" style="background-color: #333 !important;">
                        <form action="{{ route('flightops.flights.store') }}" method="POST">
                            {{ csrf_field() }}
                            <input hidden name="schedule_id" value="{{ $s->id }}"/>
                            <button type="submit" class="modal-close waves-effect waves-green btn brand-primary">Automatic Booking</button>
                            <a href="#!" class="modal-close waves-effect waves-green btn brand-red">Advanced Booking</a>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
