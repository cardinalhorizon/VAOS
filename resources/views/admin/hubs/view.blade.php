@extends('layouts.admin')
@section('head')
    <link href="{{URL::asset('https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/crewops/vendor/datatables-responsive/dataTables.responsive.css')}}" rel="stylesheet">
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a>
    </li>
    <li class="breadcrumb-item active">Hubs</li>
@endsection

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Airline Hubs
            </div>
            <div class="card-block">
                <div class="card-block">

                    @if(session('hub_created'))
                        <div class="alert alert-success">Hub successfully created.</div>
                    @elseif(session('hub_updated'))
                        <div class="alert alert-success">Hub successfully updated.</div>
                    @endif

                    <a href="{{ url('admin/airlines/hub/create') }}" role="button" class="button btn btn-primary"><i class="fa fa-plus"></i>&nbsp; New Hub</a>
                </div>
                @if($fleet == '[]')
                    <div class="alert alert-info" role="alert">
                        <strong>No Hubs Found:</strong> The server returned no hubs in the system.
                    </div>
                @else
                    <table id="table_id" class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Airline</th>
                            <th>Airport</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($hub as $a)
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