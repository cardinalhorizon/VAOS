@extends('layouts.admin')
@section('head')
    <link href="{{URL::asset('https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/crewops/vendor/datatables-responsive/dataTables.responsive.css')}}" rel="stylesheet">
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a>
    </li>
    <li class="breadcrumb-item active">PIREPs</li>
@endsection

@section('content')
    <div class="row">
    <div class="col-lg-3 col-sm12">
        <div class="card">
            <div class="card-block p-a-1 clearfix">
                <i class="fa fa-arrow-up bg-primary p-a-1 font-2xl m-r-1 pull-left"></i>
                <div class="h5 text-primary m-b-0 m-t-h">{{ $p->depapt->icao }}</div>
                <div class="text-muted text-uppercase font-weight-bold font-xs">{{ $p->depapt->name }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-12">
        <div class="card">
            <div class="card-block p-a-1 clearfix">
                <i class="fa fa-arrow-down bg-primary p-a-1 font-2xl m-r-1 pull-left"></i>
                <div class="h5 text-primary m-b-0 m-t-h">{{ $p->arrapt->icao }}</div>
                <div class="text-muted text-uppercase font-weight-bold font-xs">{{ $p->arrapt->name }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-12">
        <div class="card">
            <div class="card-block p-a-1 clearfix">
                <i class="fa fa-plane bg-primary p-a-1 font-2xl m-r-1 pull-left"></i>
                <div class="h5 text-primary m-b-0 m-t-h">{{ $p->aircraft->icao }}</div>
                <div class="text-muted text-uppercase font-weight-bold font-xs">{{ $p->aircraft->name }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-12">
        <div class="card">
            <div class="card-block p-a-1 clearfix">
                <i id="status-icon" class="fa p-a-1 font-2xl m-r-1 pull-left"></i>
                <div id="status-text" class="h5 m-b-0 m-t-h"></div>
                <div class="text-muted text-uppercase font-weight-bold font-xs">PIREP STATUS</div>
            </div>
            <div class="card-footer p-x-1 p-y-h">
                <a class="font-weight-bold font-xs btn-block text-muted" data-toggle="modal" data-target="#statusModal">Change Status <i class="fa fa-angle-right pull-right font-lg"></i></a>
            </div>
        </div>
    </div>
    </div>
    <div class="col-sm-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Crew Information
            </div>
            <div class="card-block">
                <ul class="list-group">
                    <li class="list-group-item"><div>Username<div style="position: relative; float: right;" class="float-right">{{ $p->user->username }}</div></div></li>
                    <li class="list-group-item"><div>Pilot ID<div style="position: relative; float: right;" class="float-right">{{ $p->user->pilotid }}</div></div></li>
                    <li class="list-group-item"><div>Full Name<div style="position: relative; float: right;" class="float-right">{{ $p->user->first_name }} {{ $p->user->last_name }}</div></div></li>
                    <li class="list-group-item"><div>Join Date<div style="position: relative; float: right;" class="float-right">{{ date('d/m/Y', strtotime($p->user->created_at)) }}</div></div></li>
                    <li class="list-group-item"><div>Avg Landing Rate<div style="position: relative; float: right;" class="float-right">{{ \App\PIREP::where('user_id', $p->user->id)->avg('landingrate') }}</div></div></li>
                    <li class="list-group-item"><div>Total Hours<div style="position: relative; float: right;" class="float-right">{{ \App\PIREP::where('user_id', $p->user->id)->sum('flighttime') }}</div></div></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-plane"></i> Flight Information
            </div>
            <div class="card-block">
                <ul class="list-group">
                    <li class="list-group-item"><div>Airline<div style="position:relative;float:right;" class="float-right">{{ $p->airline->name }}</div></div></li>
                    <li class="list-group-item"><div>Flight<div style="position:relative;float:right;" class="float-right">{{ $p->flightnum }}</div></div></li>
                    <li class="list-group-item"><div>Departure<div style="position:relative;float:right;" class="float-right">{{ $p->depapt->icao }} {{ $p->depapt->name }}</div></div></li>
                    <li class="list-group-item"><div>Arrival<div style="position:relative;float:right;" class="float-right">{{ $p->arrapt->icao }} {{ $p->arrapt->name }}</div></div></li>
                    <li class="list-group-item"><div>Aircraft<div style="position:relative;float:right;" class="float-right">{{ $p->aircraft->name }} - {{ $p->aircraft->registration }}</div></div></li>
                    <li class="list-group-item"><div>Distance Flown<div style="position:relative;float:right;" class="float-right">{{ $p->distance }}</div></div></li>
                    <li class="list-group-item"><div>Fuel Used<div style="position:relative;float:right;" class="float-right">{{ $p->fuel_used }}</div></div></li>
                    <li class="list-group-item"><div>Flight Time<div style="position:relative;float:right;" class="float-right">{{ $p->flighttime }}</div></div></li>
                    <li class="list-group-item"><div>Landing Rate<div style="position:relative;float:right;" class="float-right">{{ $p->landingrate }}</div></div></li>
                    <li class="list-group-item"><div>Aircraft<div style="position:relative;float:right;" class="float-right">{{ $p->aircraft->name }} - {{ $p->aircraft->registration }}</div></div></li>
                </ul>
                <button class="btn btn-primary" style="margin-top: 1rem;" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    smartCARS Logs
                </button>
                <div class="collapse" id="collapseExample" style="margin-top: 1rem;">
                    <ul class="list-group" id="scLogs">
                    </ul>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <a href="#" class="btn btn-warning" role="button" onclick="event.preventDefault();
                            document.getElementById('pending{{ $p->id }}').submit();">Pending</a>
                    <form id="pending{{ $p->id }}" method="POST" action="{{ url('/admin/pireps/'.$p->id) }}" accept-charset="UTF-8" hidden>
                        {{ csrf_field() }}
                        <input name="flag" type="hidden" value="status">
                        <input name="status" type="hidden" value="0">
                        <input name="_method" type="hidden" value="PUT">
                    </form>
                    <a href="#" class="btn btn-success" role="button" onclick="event.preventDefault();
                            document.getElementById('accept{{ $p->id }}').submit();">Accept</a>
                    <form id="accept{{ $p->id }}" method="POST" action="{{ url('/admin/pireps/'.$p->id) }}" accept-charset="UTF-8" hidden>
                        {{ csrf_field() }}
                        <input name="flag" type="hidden" value="status">
                        <input name="status" type="hidden" value="1">
                        <input name="_method" type="hidden" value="PUT">
                    </form>

                    <a href="#" class="btn btn-danger" role="button" onclick="event.preventDefault();
                            document.getElementById('reject{{ $p->id }}').submit();">Reject</a>
                    <form id="reject{{ $p->id }}" method="POST" action="{{ url('/admin/pireps/'.$p->id) }}" accept-charset="UTF-8" hidden>
                        {{ csrf_field() }}
                        <input name="flag" type="hidden" value="status">
                        <input name="status" type="hidden" value="2">
                        <input name="_method" type="hidden" value="PUT">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary">Change Status</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            // Pull the data from the API so we can add stuff
            $.getJSON( "{{ config('app.url') }}api/v1/logbook/{{$p->id}}", function( data ) {
                console.log(data);
                if(data.acars_client = "smartCARS") {
                    if (data.flight_data !== null) {
                        var logSplit = data.flight_data.split("*");
                        $.each(logSplit, function (index, value) {
                            $("#scLogs").append('<li class="list-group-item"><div>' + value + '</div></li>')
                        });
                    }
                }
                // time to apply the flight status.
                switch(data.status) {
                    case 0:
                        $("#status-icon").addClass("bg-warning fa-circle");
                        $("#status-text").addClass("text-warning").append('PENDING');
                        break;
                    case 1:
                        $("#status-icon").addClass("bg-success fa-check");
                        $("#status-text").addClass("text-success").append('APPROVED');
                        break;
                    case 2:
                        $("#status-icon").addClass("bg-danger fa-times");
                        $("#status-text").addClass("text-danger").append('DENIED');
                        break;
                }
            });
        });
    </script>
@endsection