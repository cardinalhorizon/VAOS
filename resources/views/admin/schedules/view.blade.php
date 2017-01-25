@extends('layouts.admin')
@section('head')
    <link href="{{URL::asset('https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/crewops/vendor/datatables-responsive/dataTables.responsive.css')}}" rel="stylesheet">
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a>
    </li>
    <li class="breadcrumb-item active">Schedule</li>
@endsection

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Schedules Table
            </div>
            <div class="card-block">
                <div class="card-block">

                    @if(session('schedule_created'))
                        <div class="alert alert-success">Route successfully created.</div>
                    @elseif(session('schedule_updated'))
                        <div class="alert alert-success">Route successfully updated.</div>
                    @endif

                    <a href="{{ url('admin/schedule/create') }}" role="button" class="button btn btn-primary"><i class="fa fa-plus"></i>&nbsp; New Route</a>
                </div>
                @if($schedules == '[]')
                    <div class="alert alert-info" role="alert">
                        <strong>No Routes Found:</strong> The server returned no routes in the system.
                    </div>
                @else
                <table id="table_id" class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Airline Code</th>
                        <th>Flight Number</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Aircraft Group</th>
                        <th>Seasonal</th>
                        <th>Enabled</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($schedules as $s)
                    <tr>
                        <td>{{$s->airline->icao}}</td>
                        <td>{{$s->flightnum}}</td>
                        <td>{{$s->depapt->icao}} - {{$s->depapt->name}}</td>
                        <td>{{$s->arrapt->icao}} - {{$s->arrapt->name}}</td>
                        @if($s->aircraft_group == null)
                        <td>Not Assigned</td>
                        @else
                            <td>{{$s->aircraft_group->name}}</td>
                        @endif
                        @if($s->seasonal == '1')
                            <td>Yes</td>
                        @else
                            <td>No</td>
                        @endif
                        @if($s->enabled == '1')
                            <td>Yes</td>
                        @else
                            <td>No</td>
                        @endif
                        @if($s->type == '1')
                            <td>Passenger</td>
                        @else
                            <td>Cargo</td>
                        @endif
                        <td>
                            <a href="{{ url('/admin/schedule/'.$s->id.'/edit') }}" class="btn btn-primary btn-sm">Edit</a>
                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                <a href="#" role="button" class="btn btn-danger" onclick="event.preventDefault();
                                        document.getElementById('delete-{{ $s->id }}').submit();">Delete</a>
                            </div>
                            <form id="delete-{{ $s->id }}" method="POST" action="{{ url('/admin/schedule/'.$s->id) }}" accept-charset="UTF-8" hidden>
                                {{ csrf_field() }}
                                <input name="_method" type="hidden" value="DELETE">
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                    @endif
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready( function () {
            $('#table_id').DataTable( {
                responsive: true,
                "autoWidth": false
            });
            /*
            $(".clickable-row").click(function() {
                window.document.location = $(this).data("href");
            });
            */
            $(".clickable-row").click(function() {
                return false;
            }).dblclick(function() {
                window.document.location = $(this).data("href");
                return false;
            });
        } );
    </script>
    <script src="{{URL::asset('/crewops/vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('/crewops/vendor/datatables-plugins/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('/crewops/vendor/datatables-responsive/dataTables.responsive.js')}}"></script>
@endsection