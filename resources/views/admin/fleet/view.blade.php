@extends('layouts.admin')
@section('head')
    <link href="{{URL::asset('https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/crewops/vendor/datatables-responsive/dataTables.responsive.css')}}" rel="stylesheet">
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a>
    </li>
    <li class="breadcrumb-item active">Fleet</li>
@endsection

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Aircraft Table
            </div>
            <div class="card-block">
                <div class="card-block">

                    @if(session('aircraft_created'))   
                        <div class="alert alert-success">Aircraft successfully created.</div>
                    @elseif(session('aircraft_updated'))
                        <div class="alert alert-success">Aircraft successfully updated.</div>
                    @endif

                    <a href="{{ url('admin/fleet/create') }}" role="button" class="button btn btn-primary"><i class="fa fa-plus"></i>&nbsp; New Aircraft</a>
                </div>
                @if($fleet == '[]')
                    <div class="alert alert-info" role="alert">
                        <strong>No Airplanes Found:</strong> The server returned no airplanes in the system.
                    </div>
                @else
                    <table id="table_id" class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Airline</th>
                            <th>ICAO</th>
                            <th>Manufacturer</th>
                            <th>Model Name</th>
                            <th>Registration</th>
                            <th>Hub</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($fleet as $a)
                            <tr>
                                @if($a->airline != null)
                                    <td>{{$a->airline->icao}}</td>
                                @else
                                    <td>N/A</td>
                                @endif
                                <td>{{$a->icao}}</td>
                                <td>{{$a->manufacturer}}</td>
                                <td>{{$a->name}}</td>
                                <td>{{$a->registration}}</td>
                                @if($a->hub == null)
                                    <td>Not Assigned</td>
                                @else
                                    <td>{{$a->hub->icao}}</td>
                                @endif
                                @if($a->location == null)
                                    <td>N/A</td>
                                @else
                                    <td>{{$a->location->icao}}</td>
                                @endif

                                <td>
                                    <a href="{{ url('/admin/fleet/'.$a->id.'/edit') }}" class="btn btn-primary btn-sm">Edit</a>
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
                window.document.location = this.href;
                return false;
            });
        });
    </script>
    <script src="{{URL::asset('/crewops/vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('/crewops/vendor/datatables-plugins/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('/crewops/vendor/datatables-responsive/dataTables.responsive.js')}}"></script>
@endsection