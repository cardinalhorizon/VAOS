@extends('layouts.crewops')

@section('content')
    <div class="row">
        <div class="col-lg-12">

            @if(Auth::id() == $user->id)
            <span style="right: 15px; position: absolute; bottom: 30px;"><a class="btn btn-primary" href="{{ url('flightops/profile/settings') }}"><i class="fa fa-gear"></i> Profile Settings</a></span>
            @endif

            @if(Auth::id() == $user->id)
            <h1 class="page-header">My Profile</h1>
            @else
            <h1 class="page-header">Profile - {{ $user->username }}</h1>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">User Information</h3>
                </div>
                <ul class="list-group">
                    <li class="list-group-item">
                        <strong>Database ID:</strong> <span class="pull-right">{{ $user->id }}</span>
                    </li>
                    <li class="list-group-item">
                        <strong>Username:</strong> <span class="pull-right">{{ $user->username }}</span>
                    </li>
                    <li class="list-group-item">
                        <strong>Full Name:</strong> <span class="pull-right">{{ $user->first_name . ' ' . $user->last_name }}</span>
                    </li>

                    @if($user->vatsim)
                        <li class="list-group-item">
                            <strong>VATSIM ID:</strong> <span class="pull-right">{{ $user->vatsim }}</span>
                        </li>
                    @endif

                    @if($user->ivao)
                        <li class="list-group-item">
                            <strong>IVAO ID:</strong> <span class="pull-right">{{ $user->ivao }}</span>
                        </li>
                    @endif

                    <li class="list-group-item">
                        <strong>Total Flights:</strong> <span class="pull-right">{{ count($user->pirep) }}</span>
                    </li>
                    <li class="list-group-item">
                        <strong>Registration Date:</strong> <span class="pull-right">{{ date('d/m/Y', strtotime($user->created_at)) }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Recent Flights</h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="20%">Flight</th>
                                <th width="20%">Departure</th>
                                <th width="20%">Arrival</th>
                                <th width="20%">Date</th>
                                <th width="20%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pireps as $p)
                                <tr>
                                    <td>{{ $p->airline->icao . $p->flightnum }}</td>
                                    <td>{{ $p->depapt->icao }}</td>
                                    <td>{{ $p->arrapt->icao }}</td>
                                    <td>{{ date('d/m/Y', strtotime($p->created_at)) }}</td>
                                    @if($p->status == 0)
                                        <td>
                                            <div class="label label-info">Pending</div>
                                        </td>
                                    @elseif($p->status == 1)
                                        <td>
                                            <div class="label label-success">Approved</div>
                                        </td>
                                    @elseif($p->status == 2)
                                        <td>
                                            <div class="label label-danger">Rejected</div>
                                        </td>
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