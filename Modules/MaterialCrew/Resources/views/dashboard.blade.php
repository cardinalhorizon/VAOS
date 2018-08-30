@extends('materialcrew::layouts.crewops')
@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')

    <div class="row" style="margin-top 2rem;">
        <div class="col m6 s12">
            <div class="card-offset-title">Start New Flight</div>
            <div class="card grey darken-2">
                <div class="card-content white-text" style="text-align: center;">
                    <a href="{{ route('flightops.schedule') }}" style="color: white;">
                        <span class="ibox">From Schedule <div style="font-size: 40px;padding-top:10px;"><i class="material-icons" style="font-size: 75px;">schedule</i></div></span>
                    </a>
                    <a href="{{ route('flightops.freeflight.create') }}" style="color: white;">
                        <span class="ibox">From Free Flight <div style="font-size: 40px;padding-top:10px;"><i class="material-icons" style="font-size: 75px;">add</i></div></span>
                    </a>
                </div>
            </div>
            <div style="width: 100%; height: .75px"></div>
            <div class="card-offset-title">Events</div>
            <div class="card grey darken-2">
                <div class="card-content white-text">
                    <div class="row">
                        <div class="col s12">
                            <a class="text-white" style="color: white;" href="#">
                                <div class="card" style="height: 125px; background: url('http://www.airports-worldwide.com/img/w/dfwairportoverview.jpg') black no-repeat center; background-size: cover; border-right: #6aff9a 20px solid; border-radius: 2px 5px 5px 2px">
                                    <div style="position: absolute; height: 100%; width: 100%; background-color: rgba(25,25,25,.75);z-index: 0"></div>
                                    <div style="position: absolute; bottom: 0;padding: 1rem; z-index: 5;">
                                        <div style="font-size: 2.4rem; font-weight: bold; line-height: 2.4rem;">Texas Showdown<span style="padding-left: 10px; color: #ccc; font-size: 1.4rem; font-weight: normal;">07/23/18</span></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col m6 s12">
            <div class="card-offset-title">Recent Flights</div>
            <div class="card grey darken-2">
                <div class="card-content white-text">
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
                        @foreach(\App\Models\Flight::where(['user_id' => Auth::user()->id, 'state' => 2])->orderBy('id', 'desc')->limit(10)->get() as $p)
                            <tr>
                                <td><a href="{{ url('flightops/logbook/'.$p->id) }}">{{ $p->airline->icao . $p->flightnum }}</a></td>
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
            <div style="width: 100%; height: .75px"></div>
            <div class="card-offset-title">Community Flights</div>
            <div class="card grey darken-2">
                <div class="card-content white-text">
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
                        @foreach(\App\Models\Flight::where('state', '>=', 1)->orderBy('id', 'desc')->limit(10)->get() as $p)
                            <tr>
                                <td><a href="{{ route('flightops.flights.show', ['id' => $p->id]) }}">{{ $p->airline->icao . $p->flightnum }}</a></td>
                                <td>{{ $p->depapt->icao }}</td>
                                <td>{{ $p->arrapt->icao }}</td>
                                <td>{{ date('d/m/Y', strtotime($p->created_at)) }}</td>
                                @if($p->state == 1)
                                    <td>
                                        <div class="yellow-text">Active</div>
                                    </td>
                                @elseif($p->state == 2)
                                    <td>
                                        <div class="green-text">Completed</div>
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
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">

    </script>
@endsection