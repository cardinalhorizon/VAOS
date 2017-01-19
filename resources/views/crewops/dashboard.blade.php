@extends('layouts.crewops')
@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Dashboard</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-plane fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $bids->count() }}</div>
                            <div>Active Bids</div>
                        </div>
                    </div>
                </div>
                <a href="{{ url('/flightops/bids') }}">
                    <div class="panel-footer">
                        <span class="pull-left">View Bids</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-book fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $logs->count() }}</div>
                            <div>Logged Flights</div>
                        </div>
                    </div>
                </div>
                <a href="{{ url('/flightops/logbook') }}">
                    <div class="panel-footer">
                        <span class="pull-left">View Logbook</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Your Stats</h3>
                </div>
                <div class="panel-body">
                    <ul style="list-style: none; padding-left:0;">
                        <li>Database ID: {{ Auth::user()->id }}</li>
                        <li>Name: {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</li>
                    </ul>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Profile</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Welcome to the VAOS Pilot Center Alpha/Beta</h3>
                </div>
                <div class="panel-body">
                    This pilot center is still not working at 100% however you are able to use it to request bids and
                    accept flights. Please message the official facebook page if you find a bug. ~Taylor
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Search Schedule
                </div>
                <form action="{{ url('/flightops/schedule') }}" method="GET">
                    <div class="panel-body">
                        <div class="form-group">
                            <label>Airline</label>
                            <select id="airline" name="airline" class="form-control">
                                <option value="0">Any</option>
                                @foreach(\App\Airline::all() as $a)
                                    <option value="{{ $a->id }}">{{ $a->icao }} - {{ $a->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Departure</label>
                            <select id="depapt" name="depapt" class="form-control">
                                <option value="0">Any</option>
                                @foreach(\App\Models\Airport::all() as $a)
                                    <option value="{{ $a->id }}">{{ $a->icao }} - {{ $a->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Arrival</label>
                            <select id="arrapt" name="arrapt" class="form-control">
                                <option value="0">Any</option>
                                @foreach(\App\Models\Airport::all() as $a)
                                    <option value="{{ $a->id }}">{{ $a->icao }} - {{ $a->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Aircraft</label>
                            <select id="aircraft" name="aircraft" class="form-control">
                                <option value="0">Any</option>
                                @foreach(\App\AircraftGroup::all() as $a)
                                    <option value="{{ $a->id }}">{{ $a->icao }} - {{ $a->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                        <div class="clearfix"></div>
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