@extends('layouts.crewops')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Schedule</h1>
        </div>
    </div>
    <div class="row">
        @foreach($schedules as $s)
            <div class="col-lg-4 col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-8">
                                <div class="huge">{{ $s->depapt->icao }} - {{ $s->arrapt->icao }}</div>
                            </div>
                            <div class="col-xs-4 text-right">
                                <div>@if($s->aircraft_group == null)
                                        Not Assigned
                                    @else
                                        {{$s->aircraft_group->name}}
                                    @endif</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection