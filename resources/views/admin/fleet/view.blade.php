@extends('layouts.admin2')
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
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Combined All Table
                </div>
                <div class="card-body">
                    <table class="table table-responsive-sm table-bordered table-striped table-sm">
                        <thead>
                        <tr>
                            <th>Registration</th>
                            <th>Airline</th>
                            <th>Manufacturer</th>
                            <th>ICAO</th>
                            <th>Model</th>
                            <th>Hub</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($fleet as $a)
                            <tr>
                                <td>{{$a->registration}}</td>
                                @if($a->airline != null)
                                    <td>{{$a->airline->icao}}</td>
                                @else
                                    <td>N/A</td>
                                @endif
                                <td>{{$a->manufacturer}}</td>
                                <td>{{$a->icao}}</td>
                                <td>{{$a->name}}</td>
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
                                <td>@if($a->status == 1)
                                        <span class="badge badge-success">Active</span>@else{{$a->status}}@endif</td>

                                <td>
                                    <div class="btn-group" role="group" aria-label="Actions">
                                        <a href="{{ url('/admin/fleet/'.$a->id.'/edit') }}" class="btn btn-primary btn-brand"><i class="fa fa-edit"></i></a>
                                        <button type="button" class="btn btn-secondary">Middle</button>
                                        <button type="button" class="btn btn-secondary">Right</button>
                                    </div>

                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" href="#">Prev</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">4</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
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