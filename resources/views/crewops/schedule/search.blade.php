@extends('layouts.crewops')
@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Search Schedule</h1>
        </div>
    </div>
    <div class="row">
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
                                @foreach($airlines as $a)
                                    <option value="{{ $a->id }}">{{ $a->icao }} - {{ $a->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Departure</label>
                            <select id="depapt" name="depapt" class="form-control">
                                <option value="0">Any</option>
                                @foreach($airports as $a)
                                    <option value="{{ $a->id }}">{{ $a->icao }} - {{ $a->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Arrival</label>
                            <select id="arrapt" name="arrapt" class="form-control">
                                <option value="0">Any</option>
                                @foreach($airports as $a)
                                    <option value="{{ $a->id }}">{{ $a->icao }} - {{ $a->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Aircraft</label>
                            <select id="aircraft" name="aircraft" class="form-control">
                                <option value="0">Any</option>
                                @foreach($aircraft as $a)
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