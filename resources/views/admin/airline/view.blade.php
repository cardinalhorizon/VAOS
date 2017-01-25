@extends('layouts.admin')
@section('head')
    <link href="{{URL::asset('https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/crewops/vendor/datatables-responsive/dataTables.responsive.css')}}" rel="stylesheet">
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a>
    </li>
    <li class="breadcrumb-item active">Airlines</li>
@endsection

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Airline Table
            </div>
            <div class="card-block">

                @if(session('airline_created'))
                    <div class="alert alert-success">Airline successfully created.</div>
                @elseif(session('airline_updated'))
                    <div class="alert alert-success">Airline successfully updated.</div>
                @endif

                <div class="card-block">
                    <a href="{{ url('admin/airlines/create') }}" role="button" class="button btn btn-primary"><i class="fa fa-plus"></i>&nbsp; New Airline</a>
                </div>

                <table id="table_id" class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>ICAO</th>
                        <th>IATA</th>
                        <th>Callsign</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($airlines as $a)
                        <tr>
                            <td>{{ $a->id }}</td>
                            <td>{{ $a->name}}</td>
                            <td>{{ $a->icao }}</td>
                            @if ($a->iata != null)
                                <td>{{ $a->iata }}</td>
                            @else
                                <td>N/A</td>
                            @endif
                            <td>{{ $a->callsign }}</td>
                            <td>
                                <a href="{{ url('admin/airlines/'.$a->id.'/edit') }}" class="btn btn-primary btn-sm">Edit</a>
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
                responsive: true
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