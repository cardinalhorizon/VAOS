@extends('layouts.crewops')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">My Bids</h1>
        </div>
    </div>
    <div class="row">
        @foreach($bids as $s)
            <div class="col-lg-4 col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-body" style="padding: 10px;">
                        <div class="flightpanel">
                            <div class="airline-text">{{ $s->airline->icao }}{{ $s->flightnum }}</div>
                            <div class="arrdep">{{ $s->depapt->icao }} - {{ $s->arrapt->icao }}</div>
                            <div class="flightpanel-details">
                                <div>{{$s->aircraft->name}} ({{$s->aircraft->registration}}) :<i class="fa fa-plane fa-fw"></i></div>
                                <div>{{$s->deptime}} :D<i class="fa fa-clock-o fa-fw"></i></div>
                                <div>{{$s->arrtime}} :A<i class="fa fa-clock-o fa-fw"></i></div>
                            </div>
                            <img id="airline-icon" src="{{ url('/img/AirlineLogos/LogoIcon.png') }}"/>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <span class="pull-right">
                            <a href="#" class="btn btn-primary" role="button">Details</a>
                            <a href="#" class="btn btn-success" role="button">Briefing</a>
                            <a href="#" class="btn btn-danger" role="button" onclick="event.preventDefault();
                                                     document.getElementById('delete-bid{{ $s->id }}').submit();">Cancel</a>
                            <form id="delete-bid{{ $s->id }}" method="POST" action="{{ url('/flightops/bids/'.$s->id) }}" accept-charset="UTF-8" hidden>
                                {{ csrf_field() }}
                                <input name="_method" type="hidden" value="DELETE">
                            </form>
                        </span>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection