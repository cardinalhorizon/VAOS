@extends('materialcrew::layouts.crewops')
@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link href="https://raw.githubusercontent.com/cesarve77/select2-materialize/master/select2-materialize.css" type="text/css" rel="stylesheet" >
@endsection
@section('content')
    <div>
        <div class="row">
            <form action="{{ route('flightops.freeflight.store') }}" method="POST">
                {{ csrf_field() }}
                <div class="col m6 s12">
                    <div class="card-offset-title">Departure Airport</div>
                    <div class="card grey darken-2">
                        <div class="card-content white-text">
                            <span class="card-title"></span>
                            <div class="row">
                                <div class="input-field col s12 text-white">
                                    <input placeholder="KLAX" id="arricao" type="text" name="depicao" style="color: white;" class="validate">
                                    <label for="first_name">Departure Airport</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col m6 s12">
                    <div class="card-offset-title">Arrival Airport</div>
                    <div class="card grey darken-2">
                        <div class="card-content white-text">
                            <span class="card-title"></span>
                            <div class="row">
                                <div class="input-field col s12 text-white">
                                    <input placeholder="KSFO" id="arricao" type="text" name="arricao" style="color: white;" class="validate">
                                    <label for="first_name">Arrival Airport</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col m6 s12">
                    <div class="card-offset-title">Aircraft</div>
                    <div class="card grey darken-2">
                        <div class="card-content white-text">
                            <span class="card-title"></span>
                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="aircraft">
                                        <option value="" disabled selected>Choose your aircraft</option>
                                        @foreach(\App\Models\Aircraft::all() as $a)
                                            <option value="{{$a->id}}">{{ $a->registration }} - {{ $a->icao}}</option>
                                        @endforeach
                                    </select>
                                    <label>Available Aircraft</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col m6 s12">
                    <div class="card-offset-title">Callsign</div>
                    <div class="card grey darken-2">
                        <div class="card-content white-text">
                            <span class="card-title"></span>
                            <div class="row">
                                <div class="input-field col s12">
                                    <div class="row">
                                        <div class="input-field col s12 text-white">
                                            <input placeholder="N1234T" id="callsign" type="text" name="callsign" style="color: white;" class="validate">
                                            <label for="callsign">Callsign</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn waves-effect waves-light" type="submit" name="action">Submit
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </form>
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
            $('select').formSelect();
        });
    </script>
@endsection
