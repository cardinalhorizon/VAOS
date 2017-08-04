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
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> PIREPs Table
            </div>
            <div class="card-block">

                <table id="table_id" class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Username</th>
                        <th>Airline</th>
                        <th>#</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($pireps as $p)
                        <tr>
                            <td>{{ $p->date_created }}</td>
                            <td>{{ $p->user->username }}</td>
                            <td>{{ $p->airline->icao }}</td>
                            <td>{{ $p->flightnum }}</td>
                            <td>{{ $p->depapt->icao }}</td>
                            <td>{{ $p->arrapt->icao }}</td>
                            <td>
                                <a href="{{ url('admin/pireps/'.$p->id) }}" class="btn btn-primary btn-sm">View</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

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
            $(".clickable-row").click(function() {
                window.document.location = $(this).data("href");
            });
        } );
    </script>
    <script src="{{URL::asset('/crewops/vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('/crewops/vendor/datatables-plugins/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('/crewops/vendor/datatables-responsive/dataTables.responsive.js')}}"></script>
@endsection