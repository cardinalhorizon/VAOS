@extends('layouts.crewops')
@section('customcss')

@endsection
@section('content')
    <div class="z-depth-2" style="position: relative; width: 100%; height: 300px; overflow: hidden; background: url('{{ Auth::user()->cover_url }}'), url(/img/cover_default.png);     background-repeat: no-repeat;
            background-position: center;
            background-size: cover;">
        <div style="height: 100%; background: linear-gradient(rgba(255,0,0,0), rgba(255,0,0,0), rgba(69,69,69,0.9))">
        </div>
        <h3 class="white-text" style="position: absolute; bottom: 0; left: 2rem;">{{ $user->first_name }} {{ $user->last_name }}</h3>
    </div>
    <div class="container" style="width: 90%">
        <div class="row">
            <div class="col s12 m6">
                <div class="card ">
                    <div class="card-content">
                        <span class="card-title">Recent Flights</span>
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
                            @foreach(\App\PIREP::where('user_id', $user->id)->orderBy('id', 'desc')->limit(10)->get() as $p)
                                <tr>
                                    <td>{{ $p->airline->icao . $p->flightnum }}</td>
                                    <td>{{ $p->depapt->icao }}</td>
                                    <td>{{ $p->arrapt->icao }}</td>
                                    <td>{{ date('d/m/Y', strtotime($p->created_at)) }}</td>
                                    @if($p->status == 0)
                                        <td>
                                            <div class="yellow-text">Pending</div>
                                        </td>
                                    @elseif($p->status == 1)
                                        <td>
                                            <div class="green-text">Approved</div>
                                        </td>
                                    @elseif($p->status == 2)
                                        <td>
                                            <div class="red-text">Rejected</div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col s12 m3">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">My Stats</span>
                        <ul class="collection with-header">
                            <li class="collection-item"><div>Join Date<div class="secondary-content">{{ date('d/m/Y', strtotime($user->created_at)) }}</div></div></li>
                            <li class="collection-item"><div>Total Flights<div class="secondary-content">{{ count($user->pirep) }}</div></div></li>
                            <li class="collection-item"><div>Avg Landing Rate<div class="secondary-content">{{ \App\PIREP::where('user_id', $user->id)->avg('landingrate') }}</div></div></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection