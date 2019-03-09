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
                            <div class="airline-text">{{ $s->getCallsign() }}</div>
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
                            <a href="{{url('/admin/1/pireps/'.$s->id)}}" class="btn btn-primary" role="button"><i class="fa fa-info"></i></a>
                            @if($s->flight_data !== null && $s->acars_client === "smartCARS")
                                <a href="#" class="btn btn-info" role="button" onclick="loadLog({{$s->id}});"><i class="fa fa-book"></i></a>
                            @endif
                            <a href="#" class="btn btn-success" role="button" onclick="event.preventDefault();
                                    document.getElementById('accept{{ $s->id }}').submit();"><i class="fa fa-check"></i></a>
                            <form id="accept{{ $s->id }}" method="POST" action="{{ url('/admin/1/pireps/'.$s->id) }}" accept-charset="UTF-8" hidden>
                                {{ csrf_field() }}
                                <input name="flag" type="hidden" value="status">
                                <input name="status" type="hidden" value="1">
                                <input name="_method" type="hidden" value="PUT">
                            </form>

                            <a href="#" class="btn btn-danger" role="button" onclick="event.preventDefault();
                                    document.getElementById('reject{{ $s->id }}').submit();"><i class="fa fa-times"></i></a>
                            <form id="reject{{ $s->id }}" method="POST" action="{{ url('/admin/1/pireps/'.$s->id) }}" accept-charset="UTF-8" hidden>
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
    <div class="modal fade" id="logModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">smartCARS 2 Logs</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group" id="scLogs">
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function loadLog(logbookID)
        {
            $("#scLogs").empty();
                $.getJSON(window.location.hostname+"/api/v1/logbook/" + logbookID, function( data ) {
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
                });
                console.log("data loaded");
            $('#logModal').modal('show');
        }
    </script>
@endsection