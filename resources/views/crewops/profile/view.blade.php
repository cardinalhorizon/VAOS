@extends('layouts.crewops')
@section('customcss')
    <style>
        @media (max-width: 1500px)
        {
            .MainContent {
                position: absolute;
                top: 50px;
                z-index: 0;
            }
        }
        @media (min-width: 1500px)
        {
            .MainContent {
                position: absolute;
                top: 50px;
                z-index: 0;
                margin-left: 250px;
                margin-right: 250px;
            }
        }
    </style>
@endsection
@section('content')
    <div class="MainContent">
            <div class="user-view z-depth-2">
                <div style="overflow: hidden; height: 300px; width: 100%; position: relative">
                    <img src="{{ Auth::user()->cover_url }}" onerror="this.src='{{ url('/img/cover_default.png') }}'" style="width: 100%;">
                    <div style="position: absolute; top: 40px; left: 4rem;">
                        <img class="circle" style="border: solid; border-color: white; width: 200px; height: 200px;" src="{{ Auth::user()->avatar_url }}" onerror="this.src='http://identicon.org?t={{ Auth::user()->username }}&s=400'">

                    </div>
                    <div style="position: absolute; bottom: .5rem; text-align: center; left: .5rem; align-items: center; width: 300px;">
                        <span style="font-size: 26px;" class="white-text name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    </div>

                </div>
            </div>
        <div class="row">
            <div class="col l3">
                <div class="card white">
                    <div class="card-content">
                        <span class="card-title">VA Stats</span>
                        <ul class="collection with-header">
                            <li class="collection-item"><div>Join Date<div class="secondary-content">{{ date('d/m/Y', strtotime($user->created_at)) }}</div></div></li>
                            <li class="collection-item"><div>Total Flights<div class="secondary-content">{{ count($user->pirep) }}</div></div></li>
                            <li class="collection-item"><div>Average Landing Rate<div class="secondary-content">{{ \App\PIREP::where('user_id', $user->id)->avg('landingrate') }}</div></div></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col l9">
                <div class="card white">
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
                            @foreach($pireps as $p)
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
        </div>
    </div>
@endsection