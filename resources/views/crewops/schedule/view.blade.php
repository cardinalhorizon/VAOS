@extends('layouts.crewops')
@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link href="https://raw.githubusercontent.com/cesarve77/select2-materialize/master/select2-materialize.css" type="text/css" rel="stylesheet" >
@endsection
@section('content')
    <div>
        <div class="row">
            <div class="col s10">
                {{ $schedules->appends(\Illuminate\Support\Facades\Input::except('page'))->links('vendor.pagination.material') }}
            </div>
            <div class="col s2">

            </div>
            @foreach($schedules as $s)
                <div class="col xl6 l12 m12 s12">
                    <a class="text-white modal-trigger" style="color: white;" href="#modal{{$s->id}}">
                        <div class="card hoverable" style="height: 175px; background: url('{!! $s->arrapt->banner_url !!}') black no-repeat center; background-size: cover; border-right: #6aff9a 20px solid; border-radius: 2px 5px 5px 2px">
                            <div style="position: absolute; height: 100%; width: 100%; background-color: rgba(25,25,25,.65);z-index: 0"></div>
                            <div class="card-content" style="position: relative; z-index: 5;height:175px;display:block;">
                                <div style="font-size: 2.4rem; font-weight: bold; line-height: 3rem;">{{ $s->airline->icao }}{{ $s->flightnum }}</div>
                                <div style="color: #ddd; font-size: 1.4rem; position: relative; margin-top: 65px; bottom: 0; width: 100%; font-weight: normal; display: flex;justify-content: space-between;">
                                    <span><i class="material-icons" style="font-size: 1.4rem;">flight_takeoff</i> {{ $s->depapt->icao }} | {{ $s->deptime }}</span>
                                    <span><i class="material-icons" style="font-size: 1.4rem;">flight_land</i> {{ $s->arrapt->icao }} | {{ $s->arrtime }}</span>
                                    <span><i class="material-icons" style="font-size: 1.4rem;">flight</i> {{ $s->primary_aircraft }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div id="modal{{$s->id}}" class="modal">
                    <div class="modal-content">
                        <h4>{{ $s->airline->icao }}{{ $s->flightnum }}</h4>
                        <p>A bunch of text</p>
                    </div>
                    <div class="modal-footer" style="background-color: #333 !important;">
                        <form action="{{ route('flightops.flights.store') }}" method="POST">
                            {{ csrf_field() }}
                            <input hidden name="schedule_id" value="{{ $s->id }}"/>
                            <button type="submit" class="modal-close waves-effect waves-green btn brand-primary">Automatic Booking</button>
                            <a href="#!" class="modal-close waves-effect waves-green btn brand-red">Advanced Booking</a>
                        </form>
                    </div>
                </div>

                {{--
                <div class="col m6 s12">
                    <div class="card">
                        <div class="card-image grey darken-3" style="height:150px; overflow: hidden;">
                            <img class="activator" src="https://raw.githubusercontent.com/CardinalHorizon/VAOS/master/public/img/login.png">
                            <img style="width: 150px; height: 150px; position: absolute;" src="{{ $s->airline->widget }}">
                            <span style="bottom: -20px; font-size: 30px;z-index: 1;" class="card-title">{{ $s->airline->icao }}{{ $s->flightnum }}</span>
                        </div>

                        <div class="card-content">
                            <a style="position: absolute;right: 24px;bottom: 135px;" class="btn-floating activator waves-effect waves-light red"><i class="material-icons">more_vert</i></a>
                            <span style="display: inline-flex; vertical-align: middle;" class="card-title activator grey-text text-darken-4">{{ $s->depapt->iata }}<i class="material-icons">&#xE5C8;</i>{{ $s->arrapt->iata }}<i class="material-icons"></i></span>
                        </div>
                        <div class="card-reveal" style="z-index: 2;">
                            <span class="card-title grey-text text-darken-4">{{ $s->airline->icao }}{{ $s->flightnum }}<i class="material-icons right">close</i></span>
                            <ul class="collection with-header">
                                <li class="collection-item"><div>Aircraft Group<div class="secondary-content">@if($s->aircraft_group == "[]")
                                                Not Assigned
                                            @else
                                                @foreach($s->aircraft_group as $acf)
                                                    {{$acf->name}},
                                                @endforeach
                                            @endif</div></div></li>
                                <li class="collection-item"><div>Airline<div class="secondary-content">{{ $s->airline->name }}</div></div></li>
                            </ul>
                        </div>
                        <form action="{{ route('flightops.flights.store') }}" method="POST">
                            {{ csrf_field() }}
                            <input hidden name="schedule_id" value="{{ $s->id }}"/>
                            <div class="card-action">
                                @if(Auth::guest())
                                    <b>PLEASE LOGIN TO BID ON FLIGHT</b>
                                @else
                                    <button type="submit" class="btn green">Simple Flight</button>
                                    <a class="btn blue" disabled>Advanced Flight</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
                --}}
            @endforeach
        </div>
    </div>
    <!-- Search Modal -->
    <div id="modalsearch" class="modal">
        <form action="{{ route('flightops.schedule') }}" method="GET">
            <div class="modal-content">
                <h4>Search Routes</h4>
                <div class="row" style="margin-bottom: 0;">
                    <div class="col s12">
                        <div class="row" style="margin-bottom: 0;">
                            <div class="input-field col s12">
                                <input placeholder="Any" list="apt" name="depapt" type="text">
                                <datalist id="apt">
                                    <option value="0" selected>Any</option>
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-flat green darken-3 white-text" type="submit">Search</button>
                <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat red white-text">Cancel</a>
            </div>
        </form>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.modal').modal();
            $('#airline').select2();
            $('#depapt').select2();
            $('#arrapt').select2();
            $('#aircraft').select2();
        });
    </script>
@endsection
