@extends('layouts.crewops')
@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
    <div class="container" style="width: 90%">
        <div class="row">
            <div class="col s12 m3">
                <div class="card">
                    <form action="{{ url('/flightops/schedule') }}" method="GET">
                        <div class="card-content">
                            <span class="card-title">Search For Flights</span>
                            <div class="row" style="margin-bottom: 0;">
                                <div class="col s12">
                                    <div class="row" style="margin-bottom: 0;">
                                        <div class="input-field col s12">
                                            <input placeholder="Any" list="apt" name="depapt" type="text">
                                            <datalist id="apt">
                                                @foreach(App\Models\Airport::all() as $a)
                                                    <option value="{{ $a->icao }}">{{ $a->name }}</option>
                                                @endforeach
                                            </datalist>
                                            <label>Departure Airport</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input placeholder="Any" list="apt" name="arrapt" type="text">
                                            <label>Arrival Airport</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input placeholder="Any" list="acf" name="aircraft_group" type="text">
                                            <label>Aircraft</label>
                                            <datalist id="acf">
                                                @foreach(App\AircraftGroup::all() as $a)
                                                    <option value="{{ $a->icao }}">{{ $a->name }}</option>
                                                @endforeach
                                            </datalist>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <button class="btn green darken-3" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>
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
                            @foreach(\App\PIREP::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->limit(10)->get() as $p)
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
            </div>
            <div class="col s12 m3">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">My Stats</span>
                        <ul class="collection with-header">
                            <li class="collection-item"><div>Join Date<div class="secondary-content">{{ date('d/m/Y', strtotime(Auth::user()->created_at)) }}</div></div></li>
                            <li class="collection-item"><div>Total Flights<div class="secondary-content">{{ count(Auth::user()->pirep) }}</div></div></li>
                            <li class="collection-item"><div>Avg Landing Rate<div class="secondary-content">{{ \App\PIREP::where('user_id', Auth::user()->id)->avg('landingrate') }}</div></div></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#airline').select2();
            $('#depapt').select2();
            $('#arrapt').select2();
            $('#aircraft').select2();
        });
    </script>
@endsection