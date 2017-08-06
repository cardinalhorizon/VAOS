@extends('layouts.crewops')
@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
        <div class="row" style="">
            <div class="col l8 offset-l2">
                <div class="card">
                    <div class="card-image" style="overflow: hidden; height: 300px;">
                        <img
                             src="https://raw.githubusercontent.com/CardinalHorizon/VAOS/master/public/img/login.png">
                        <span style="font-size: 30px;" class="card-title">Airline Schedule</span>
                    </div>
                    <form action="{{ url('/flightops/schedule') }}" method="GET">
                    <div class="card-content">
                        <div class="row" style="margin-bottom: 0;">
                            <div class="col s12">
                                <div class="row" style="margin-bottom: 0;">
                                    <div class="input-field col s6">
                                        <select name="depapt">
                                            <option value="0" selected>Any</option>
                                            @foreach(App\Models\Airport::all() as $a)
                                                <option value="{{ $a->id }}">{{ $a->icao }} - {{ $a->name }}</option>
                                            @endforeach
                                        </select>compcompocompioasdffasdf
                                        <label>Departure Airport</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <select name="arrapt">
                                            <option value="0" selected>Any</option>
                                            @foreach(App\Models\Airport::all() as $a)
                                                <option value="{{ $a->id }}">{{ $a->icao }} - {{ $a->name }}</option>
                                            @endforeach
                                        </select>
                                        <label>Arrival Airport</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="btn green darken-3" type="submit">Apply Filter</button>
                        <div class="right">


                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <div class="col s12">
                {{ $schedules->appends(\Illuminate\Support\Facades\Input::except('page'))->links('vendor.pagination.material') }}
            </div>

        </div>
    <div class="container">
        <div class="row">
            @foreach($schedules as $s)
                <div class="col s6">
                    <div class="card sticky-action">
                        <div class="card-image grey darken-3" style="height:150px; overflow: hidden;">
                            <!-- <img class="activator" src="https://raw.githubusercontent.com/CardinalHorizon/VAOS/master/public/img/login.png"> -->
                            <img style="width: 150px; height: 150px; position: absolute;" src="{{ $s->airline->widget }}">
                            <span style="bottom: -20px; font-size: 30px;z-index: 1;" class="card-title">{{ $s->airline->icao }}{{ $s->flightnum }}</span>
                        </div>

                        <div class="card-content">
                            <a style="position: absolute;right: 24px;bottom: 135px;" class="btn-floating activator waves-effect waves-light red"><i class="material-icons">more_vert</i></a>
                            <span style="display: inline-flex; vertical-align: middle;" class="card-title activator grey-text text-darken-4">{{ $s->depapt->icao }}<i class="material-icons">&#xE5C8;</i>{{ $s->arrapt->icao }}<i class="material-icons"></i></span>
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
                                <button type="submit" class="btn green">Simple Bid</button>
                                <a class="btn blue" disabled>Advanced Bid</a>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('select').material_select();
        });
    </script>
@endsection