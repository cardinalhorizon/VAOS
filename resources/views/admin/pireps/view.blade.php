@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a></li>
    <li class="breadcrumb-item active">PIREPs</li>
@endsection
@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> PIREPs Table
            </div>
            <div class="card-block">
                <div class="card-block">
                    {{-- TODO: Finishing View --}}
                    {{--@if(session('aircraft_created'))
                        <div class="alert alert-success">Aircraft successfully created.</div>
                    @elseif(session('aircraft_updated'))
                        <div class="alert alert-success">Aircraft successfully updated.</div>
                    @endif--}}

                    {{--<a href="{{ url('admin/fleet/create') }}" role="button" class="button btn btn-primary"><i class="fa fa-plus"></i>&nbsp; New Aircraft</a>--}}
                </div>
                @if($pireps == '[]')
                    <div class="alert alert-info" role="alert">
                        <strong>No PIREPs Found:</strong> The server returned no PIREPs in the system.
                    </div>
                @else
                    <table id="table_id" class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>User</th>
                            <th>Airline</th>
                            <th>Departure Airport</th>
                            <th>Arrival Airport</th>
                            <th>Aircraft Type</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pireps as $a)
                            <tr>
                                <td>{{$a->user}}</td>
                                <td>{{$a->airline}}</td>
                                <td>{{$a->depapt}}</td>
                                <td>{{$a->arrapt}}</td>
                                <td>{{$a->aircraft}}</td>
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