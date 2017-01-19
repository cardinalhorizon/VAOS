@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ url('admin/pireps') }}">PIREPs</a></li>
    <li class="breadcrumb-item active">Pending</li>
@endsection
@section('content')
    <div class="row">
        @foreach($pireps as $s)
            <div class="col-lg-4 col-sm-12">
                <div class="card">
                    <div class="card-block" style="padding: 10px;">
                        <div class="flightpanel">
                            <div class="airline-text">{{ $s->airline->icao }}{{ $s->flightnum }}</div>
                            <div class="arrdep">{{ $s->depapt->icao }} - {{ $s->arrapt->icao }}</div>
                            <div class="flightpanel-details">
                                <div>{{$s->user->first_name}} {{$s->user->last_name}} :<i class="fa fa-user fa-fw"></i></div>
                                <div>{{$s->aircraft->name}} ({{$s->aircraft->registration}}) :<i class="fa fa-plane fa-fw"></i></div>
                                <div>{{$s->landingrate}} :LR</div>
                            </div>
                            <img id="airline-icon" src="{{ url('/img/AirlineLogos/LogoIcon.png') }}"/>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="pull-right">
                            <a href="#" class="btn btn-primary" role="button">Details</a>
                            <a href="#" class="btn btn-success" role="button" onclick="event.preventDefault();
                                    document.getElementById('accept{{ $s->id }}').submit();">Accept</a>
                            <form id="accept{{ $s->id }}" method="POST" action="{{ url('/admin/pireps/'.$s->id) }}" accept-charset="UTF-8" hidden>
                                {{ csrf_field() }}
                                <input name="flag" type="hidden" value="status">
                                <input name="status" type="hidden" value="1">
                                <input name="_method" type="hidden" value="PUT">
                            </form>

                            <a href="#" class="btn btn-danger" role="button" onclick="event.preventDefault();
                                    document.getElementById('reject{{ $s->id }}').submit();">Reject</a>
                            <form id="reject{{ $s->id }}" method="POST" action="{{ url('/admin/pireps/'.$s->id) }}" accept-charset="UTF-8" hidden>
                                {{ csrf_field() }}
                                <input name="flag" type="hidden" value="status">
                                <input name="status" type="hidden" value="2">
                                <input name="_method" type="hidden" value="PUT">
                            </form>
                        </span>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection