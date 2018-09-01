@extends('materialcrew::layouts.crewops')
@section('customcss')
    <style>
        .info-block {
            text-align: center;
            color: white;
        }
        .info-block-title {
            font-size: 2.6rem;
        }
        .info-block-item {
            font-size: 3.7rem;
            font-weight: bold;
        }
        .tab>a {
            color: white/* #61c7ff */ !important;
            text-transform: none !important;
            font-size: 1.73rem !important;
        }
        .tabs {
            display: flex;
            justify-content: space-between;

        }
        .tab-panel {
            margin-top: 2rem;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col s12">
            <div class="card grey darken-3" style="background: url('{{ $user->cover_url }}'), url('https://i.imgur.com/qOXmrci.png');     background-repeat: no-repeat;
                    background-position: center;
                    background-size: cover;">
                <div style="position: absolute; height: 100%; width: 100%; background-color: rgba(25,25,25,.75);z-index: 0"></div>
                <div class="card-content" style="display: flex;justify-content: space-between;position: relative; z-index: 5;">
                    <div style="display: inline-flex; margin-top: auto; margin-bottom: auto;">
                        <div class="circle" style="border: solid; border-color: white; width: 150px; height: 150px;background: url('{!! $user->avatar_url !!}'), url('http://identicon.org?t={{$user->username}}&amp;s=400') black no-repeat center; background-size: cover;"></div>
                        <div style="position: relative; margin-top: auto; margin-bottom: auto; margin-right: 0;">
                            <div style="padding-left: 10px; color:white; font-size: 30px;">{{ $user->first_name }} {{ $user->last_name }}</div>
                            <div style="height: 1px; width: 110%; background: white; border: 2px white solid; border-radius: 0 2px 2px 0;"></div>
                            <div style="padding-left: 10px; color:#61C7FF;font-size: 28px;">{{ $user->username }}</div>
                        </div>
                    </div>
                    <div class="info-block">
                        <div class="info-block-title">Total Flights</div>
                        <div class="info-block-item">0</div>
                    </div>
                    <div class="info-block">
                        <div class="info-block-title">Avg Landing Rate</div>
                        <div class="info-block-item">0</div>
                    </div>
                    <div class="info-block" style="text-align: right;margin-top: auto;">
                        <div>Joined: 12/22/2018</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col m8 s12 offset-m2">
            <ul class="tabs transparent">
                <li class="tab"><a class="active" href="#tabFlights">Flights</a></li>
                <li class="tab"><a href="#tabAircraft">Aircraft</a></li>
                <li class="tab"><a href="#tabAirlines">Airlines</a></li>
                <li class="tab"><a href="#tabStats">Stats</a></li>
                @if($user->id == Auth::user()->id)
                    <li class="tab"><a href="#tabSettings">Settings</a></li>
                @endif
            </ul>
        </div>
        <div id="tabFlights" class="col s12 tab-panel">
            <div class="col m10 s12 offset-m1">
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
                            @foreach(\App\Models\Flight::completed()->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->limit(10)->get() as $p)
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
        </div>
        <div id="tabAircraft" class="col m10 s12 offset-m1 tab-panel">
            <div class="row">
                @foreach(\App\Models\Aircraft::where('user_id', Auth::user()->id)->get() as $a)
                <div class="col xl6 l12 m12 s12">
                    <a class="text-white modal-trigger" style="color: white;" href="#">
                        <div class="card hoverable" style="height: 175px; background: url('https://airfactsjournal.com/files/2013/11/172-sales.jpg') black no-repeat center; background-size: cover; border-right: #6aff9a 20px solid; border-radius: 2px 5px 5px 2px">
                            <div style="position: absolute; height: 100%; width: 100%; background-color: rgba(25,25,25,.65);z-index: 0"></div>
                            <div class="card-content" style="position: relative; z-index: 5;height:175px;display:block;">
                                <div style="font-size: 2.4rem; font-weight: bold; line-height: 3rem;">{{$a->registration}}</div>
                                <div style="color: #ddd; font-size: 1.4rem; position: relative; margin-top: 65px; bottom: 0; width: 100%; font-weight: normal; display: flex;justify-content: space-between;">
                                    <span><i class="material-icons" style="font-size: 1.4rem;">flight</i> {{$a->icao}}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            <div style="position: fixed; z-index: 10; right: 4rem; bottom: 4rem;"><a id="addAircraft" class="btn-floating btn-large waves-effect waves-light modal-trigger" href="#modalAddAircraft" style="background-color: #61c7ff;"><i class="material-icons">add</i></a></div></div>
            <div id="modalAddAircraft" class="modal">
                    <form action="{{ route('flightops.profile.addAircraft') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="modal-content">
                            <h4>Add Personal Aircraft</h4>
                            <div class="row" style="margin-bottom: 0;">
                                <div class="input-field col s12">
                                    <input id="icao" name="icao" type="text" class="validate">
                                    <label for="icao">ICAO</label>
                                </div>
                                <div class="input-field col s12">
                                    <input id="name" name="name" type="text" class="validate">
                                    <label for="name">Name</label>
                                </div>
                                <div class="input-field col s12">
                                    <input id="manufacturer" name="manufacturer" type="text" class="validate">
                                    <label for="manufacturer">Manufacturer</label>
                                </div>
                                <div class="input-field col s12">
                                    <input id="registration" name="registration" type="text" class="validate">
                                    <label for="registration">Registration</label>
                                </div>
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit" name="action">Submit
                                        <i class="material-icons right">send</i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn-flat green darken-3 white-text" type="submit">Search</button>
                            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat red white-text">Cancel</a>
                        </div>
                    </form>
            </div>
        <div id="tabAirlines" class="col s12 tab-panel">
            <div class="row">
                <div class="col xl12 l12 m12 s12">
                    <div class="card grey darken-3" style="background: url('http://fsvaos.net/img/Banner2.png');    background-repeat: no-repeat;
                            background-position: center;
                            background-size: cover;">
                        <div style="position: absolute; height: 100%; width: 100%; background-color: rgba(25,25,25,.75);z-index: 0"></div>
                        <div class="card-content" style="display: flex;justify-content: space-between;position: relative; z-index: 5;">
                            <div style="display: inline-flex; margin-top: auto; margin-bottom: auto;">
                                <div style="width: 150px; height: 150px;background: url('{{ url('/img/AirlineLogos/LogoIcon.png') }}') no-repeat center; background-size: cover;"></div>
                                <div style="position: relative;margin-right: 0;">
                                    <div style="padding-left: 10px; color:white; font-size: 2.4rem;">Airline Name</div>
                                </div>
                            </div>
                            <div class="info-block" style="text-align: right;margin-top: auto;">
                                <div>Joined: 12/22/2018</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="tabStats" class="col s12 tab-panel">Test 4</div>
        @if($user->id == Auth::user()->id)
            <div id="tabSettings" class="col s12 tab-panel">Test 4</div>
        @endif
    </div>
@endsection
@section('js')
    <script>
    $(document).ready(function(){
        $('.tabs').tabs();
    });
    </script>
@endsection()