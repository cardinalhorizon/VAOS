@extends('layouts.crewops')
@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <span style="right: 15px; position: absolute; bottom: 30px;"><button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalSearch">Search Schedule</button></span>
            <h1 class="page-header">Schedule</h1>
        </div>
    </div>
    <div class="row">
        {{ $schedules['data'] }}
        @if($schedules['data'] == '[]')
            <div class="col-sm-12">
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Routes Not Found.</strong> The search parameters you entered yielded no results.
            </div>
            </div>
        @else
        @foreach($schedules as $s)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-body" style="padding: 10px;">
                        <div class="flightpanel">
                            <div class="airline-text">{{ $s->airline->icao }}{{ $s->flightnum }}</div>
                            <div class="arrdep">{{ $s->depapt->icao }} - {{ $s->arrapt->icao }}</div>
                            <div class="flightpanel-details">
                                <div>@if($s->aircraft_group == null)
                                        Not Assigned
                                    @else
                                        {{$s->aircraft_group->name}}
                                    @endif
                                    <i class="fa fa-plane fa-fw"></i>
                                </div>
                            </div>
                            <img id="airline-icon" src="{{ url('/img/AirlineLogos/LogoIcon.png') }}"/>
                        </div>
                    </div>
                    <form action="{{ url('/flightops/bids') }}" method="POST">
                    <div class="panel-footer">
                        <span class="pull-left">
                            {{ csrf_field() }}
                                <input hidden name="schedule_id" value="{{ $s->id }}"/>
                                @if($s->aircraft_group == null)
                                    <select id="airline" name="airline" class="form-control" size="1">
                                        @foreach($aircraft as $a)
                                            <option value="{{ $a->id }}">{{ $a->name }} - {{ $a->registration }}</option>
                                        @endforeach
                                    </select>
                                @endif
                        </span>
                        <span class="pull-right">
                            <button type="submit" class="btn btn-primary">Bid</button>
                            <!-- <a href="{{ url('/flightops/bids/create?schedule='.$s->id) }}" class="btn btn-info" role="button">Adv. Bid</a> -->
                        </span>
                        <div class="clearfix"></div>
                    </div>
                    </form>
                </div>
            </div>
        @endforeach
            <div class="col-sm-12"> {!! $schedules->appends(\Illuminate\Support\Facades\Input::except('page'))->links() !!}</div>
            @endif
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalSearch" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Search Routes</h4>
                </div>
                <form action="{{ url('/flightops/schedule') }}" method="GET">
                <div class="modal-body">
                            <div class="form-group">
                                <label>Airline</label>
                                <select id="airline" name="airline" class="form-control" style="width: 100%;">
                                    <option value="0">Any</option>
                                    @foreach(\App\Airline::all() as $a)
                                        <option value="{{ $a->id }}">{{ $a->icao }} - {{ $a->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Departure</label>
                                <select id="depapt" name="depapt" class="form-control" style="width: 100%;">
                                    <option value="0">Any</option>
                                    @foreach(\App\Models\Airport::all() as $a)
                                        <option value="{{ $a->id }}">{{ $a->icao }} - {{ $a->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Arrival</label>
                                <select id="arrapt" name="arrapt" class="form-control" style="width: 100%;">
                                    <option value="0">Any</option>
                                    @foreach(\App\Models\Airport::all() as $a)
                                        <option value="{{ $a->id }}">{{ $a->icao }} - {{ $a->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Aircraft</label>
                                <select id="aircraft" name="aircraft" class="form-control" style="width: 100%;">
                                    <option value="0">Any</option>
                                    @foreach(\App\AircraftGroup::all() as $a)
                                        <option value="{{ $a->id }}">{{ $a->icao }} - {{ $a->name }}</option>
                                    @endforeach
                                </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#airline').select2();
            $('#depapt').select2();
            $('#arrapt').select2();
            $('#aircraft').select2();
        });
    </script>
@endsection