@extends('layouts.crewops')
@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link href="https://raw.githubusercontent.com/cesarve77/select2-materialize/master/select2-materialize.css" type="text/css" rel="stylesheet" >
@endsection
@section('content')
    <div class="z-depth-2" style="position: relative; width: 100%; height: 300px; overflow: hidden; background: url(http://i.imgur.com/3UZDNCM.png);     background-repeat: no-repeat;
            background-position: center;
            background-size: cover;">
        <div style="height: 100%; background: linear-gradient(rgba(255,0,0,0), rgba(255,0,0,0), rgba(69,69,69,0.9))">
        </div>
        <h3 class="white-text" style="position: absolute; bottom: 0; left: 2rem;">Airline Schedule</h3>
        <div class="container" style="position: inherit;">
            <div style="position: absolute; right: 0; bottom: 1rem;">
                <a class="waves-effect waves-light btn modal-trigger" href="#modalsearch">Search</a>
            </div>
        </div>
        <a class="waves-effect waves-light btn modal-trigger" href="#modalsearch">Search</a>
    </div>
    <div class="container">
        <div class="row">
            <div class="col s10">
                {{ $schedules->appends(\Illuminate\Support\Facades\Input::except('page'))->links('vendor.pagination.material') }}
            </div>
            <div class="col s2">

            </div>
            @foreach($schedules as $s)
                <div class="col m6 s12">
                    <div class="card">
                        <div class="card-image grey darken-3" style="height:150px; overflow: hidden;">
                            <!-- <img class="activator" src="https://raw.githubusercontent.com/CardinalHorizon/VAOS/master/public/img/login.png"> -->
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
                                <li class="collection-item"><div>Aircraft Group<div class="secondary-content">@if($s->aircraft_group == null)
                                                Not Assigned
                                            @else
                                                {{$s->aircraft_group->name}}
                                            @endif</div></div></li>
                                <li class="collection-item"><div>Airline<div class="secondary-content">{{ $s->airline->name }}</div></div></li>
                            </ul>
                        </div>
                        <form action="{{ url('/flightops/bids') }}" method="POST">
                            {{ csrf_field() }}
                            <input hidden name="schedule_id" value="{{ $s->id }}"/>
                            <div class="card-action">
                                @if(Auth::guest())
                                    <b>PLEASE LOGIN TO BID ON FLIGHT</b>
                                @else
                                    <button type="submit" class="btn green">Simple Bid</button>
                                    <a class="btn blue" disabled>Advanced Bid</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <!-- Search Modal -->
    <div id="modalsearch" class="modal">
        <form action="{{ url('/schedule') }}" method="GET">
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

    </script>
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