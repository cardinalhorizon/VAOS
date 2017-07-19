@extends('layouts.crewops')

@section('head')
    <link href="{{URL::asset('/crewops/vendor/datatables-plugins/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/crewops/vendor/datatables-responsive/dataTables.responsive.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Roster</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9 col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <table id="table_id" class="table table-striped table-bordered table-hover table-responsive">
                        <thead>
                        <tr>
                            <th width="10%">ID</th>
                            <th width="25%">Username</th>
                            <th width="25%">First Name</th>
                            <th width="25%">Last Name</th>
                            <th width="15%">Total Flights</th>
                            <th>Landing Rate Avg</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $u)
                            <tr>
                                <td>{{ $u->id }}</td>
                                <td><a href="{{ url('flightops/profile/' . $u->id) }}">{{ $u->username }}</a></td>
                                <td>{{ $u->first_name }}</td>
                                <td>{{ $u->last_name }}</td>
                                <td>{{ count($u->pirep) }}</td>
                                <td>{{ \App\PIREP::where('user_id', $u->id)->avg('landingrate') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready( function () {
            $('#table_id').DataTable( {
                responsive: true
            });
        } );
    </script>
    <script src="{{URL::asset('/crewops/vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('/crewops/vendor/datatables-plugins/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('/crewops/vendor/datatables-responsive/dataTables.responsive.js')}}"></script>
@endsection