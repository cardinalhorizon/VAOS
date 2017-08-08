@extends('layouts.admin')
@section('head')
    <link href="{{URL::asset('https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/crewops/vendor/datatables-responsive/dataTables.responsive.css')}}" rel="stylesheet">
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a>
    </li>
    <li class="breadcrumb-item"><a href="{{ url('admin/users') }}">Users</a></li>
    <li class="breadcrumb-item active">{{ $user->username }}</li>
@endsection

@section('content')
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-edit"></i> Edit Details
            </div>
            <div class="card-block">
                @if(count($errors))
                    <div class="alert alert-danger">
                        <strong>The following error(s) occurred:</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @elseif(session('user_updated'))
                    <div class="alert alert-success">User details successfully updated.</div>
                @endif
                <form action="{{ url('admin/users/'.$user->id) }}" method="post">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" name="first_name" value="{{ $user->first_name }}">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" name="last_name" value="{{ $user->last_name }}">
                    </div>
                    <div class="form-group">
                        <label>Email address</label>
                        <input type="text" class="form-control" name="email" value="{{ $user->email }}">
                    </div>
                    <div class="form-group">
                        <label>VATSIM ID</label>
                        <input type="text" class="form-control" name="vatsim" value="{{ $user->vatsim }}">
                    </div>
                    <div class="form-group">
                        <label>IVAO ID</label>
                        <input type="text" class="form-control" name="ivao" value="{{ $user->ivao }}">
                    </div>
                    <div class="form-group">
                        <label>Cover URL</label>
                        <input type="text" class="form-control" name="cover_url" value="{{ $user->cover_url }}">
                    </div>
                    <div class="form-group">
                        <label>Avatar URL</label>
                        <input type="text" class="form-control" name="avatar_url" value="{{ $user->avatar_url }}">
                    </div>
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> All PIREPs
            </div>
            <div class="card-block">
                <table id="table_id" class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Airline</th>
                        <th>Flight</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Aircraft</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($user->pirep as $p)
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
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready( function () {
            $('#table_id').DataTable( {
                responsive: true,
                "autoWidth": false
            });
        } );
    </script>
    <script src="{{URL::asset('/crewops/vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('/crewops/vendor/datatables-plugins/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('/crewops/vendor/datatables-responsive/dataTables.responsive.js')}}"></script>
@endsection