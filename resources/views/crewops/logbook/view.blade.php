@extends('layouts.crewops')

@section('head')
    <link href="{{URL::asset('/crewops/vendor/datatables-plugins/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/crewops/vendor/datatables-responsive/dataTables.responsive.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">My Logbook</h1>
        </div>
    </div>
    <div class="row">
            <div class="col-lg-9 col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <table id="table_id" class="table table-striped table-bordered table-hover table-responsive">
                            <thead>
                            <tr>
                                <th>Airline</th>
                                <th>Flight</th>
                                <th>Departure</th>
                                <th>Arrival</th>
                                <th>Aircraft</th>
                                <th>Approved</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($pireps as $p)
                                <tr>
                                    <td>{{ $p->airline->icao }}</td>
                                    <td>{{ $p->flightnum }}</td>
                                    <td>{{ $p->depapt->icao }}</td>
                                    <td>{{ $p->arrapt->icao }}</td>
                                    <td>{{ $p->aircraft->name }} ({{ $p->aircraft->registration }})</td>
                                    @if($p->status === 1)
                                        <td>Approved</td>
                                    @elseif($p->status === 2)
                                        <td>Denied</td>
                                    @else
                                        <td>Pending</td>
                                    @endif
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