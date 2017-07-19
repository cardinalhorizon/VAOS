<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="{{ URL::asset('/bower_components/materialize/dist/css/materialize.css') }}"  media="screen,projection"/>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <title>Crew Operations</title>
    @yield('customcss')
</head>
<body class="grey lighten-3">
<nav class="blue darken-3" style="position: absolute; top: 0; z-index: 888">
    <div class="nav-wrapper">

        <a href="#" class="brand-logo" style="margin-left: 18rem;">{{ config('app.name', 'VAOS') }} Flight Operations</a>
        <ul id="nav-mobile" style="margin-right: 2rem;" class="right hide-on-med-and-down">
            <li>VAOS Version 1.0 Release Candidate</li>
        </ul>
    </div>
</nav>
<!-- Personal Menu Side Popout -->
<div class="blue darken-4 z-depth-2" style="width: 250px; height: 64px; padding-left: 1rem; position: absolute; top: 0px; z-index: 955">
<a href="#" id="slide-button" style="color: white;" data-activates="slide-out">
    <div style="height: 64px; width: 100%;position: relative;">
        <div style="height: 58px; width: 58px; margin-top: .2rem; overflow: hidden;" class="circle">
            <img style="height: 58px;" src="{{ Auth::user()->avatar_url }}" onerror="this.src='http://identicon.org?t={{ Auth::user()->username }}&s=400'">
        </div>
        <span style="position: absolute; top: 25%; left: 64px; font-size: 20px;">{{ Auth::user()->username }}</span>
    </div>
</a>
</div>

<div id="iBar" style="height: 100%; width: 250px; margin-top: 64px;" class="hide-on-med-and-down">
    <!--<ul>
        <li>

        </li>
        <li style="height: 44px; padding-left: 1rem; background-color: #CCCCCC">
            <a href="#">
                <div style="line-height: 44px;">Link Text</div>
            </a>
        </li>
    </ul> -->
</div>


<ul id="slide-out" class="side-nav">
    <li>
        <div class="user-view">
            @if(Auth::user()->admin)
                <span class="red darken-3 white-text" style="position: absolute;bottom: 7px;right: 0;line-height: 20px;padding: 0 10px;">STAFF</span>
            @endif
            <div class="background">
                <img src="{{ Auth::user()->cover_url }}" onerror="this.src='{{ url('/img/cover_default.png') }}'" style="width:100%;">
            </div>
            <a href="{{ url('/flightops/profile/'.Auth::user()->id) }}"><img class="circle" src="{{ Auth::user()->avatar_url }}" onerror="this.src='http://identicon.org?t={{ Auth::user()->username }}&s=400'"></a>
            <a href="{{ url('/flightops/profile/'.Auth::user()->id) }}"><span class="white-text name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span></a>
            <a href="{{ url('/flightops/profile/'.Auth::user()->id) }}"><span class="white-text email">{{ Auth::user()->username }}</span></a>
        </div>
    </li>

        <li><a href="{{ url('/flightops/community/') }}">Community Center</a></li>
        <li><a href="{{ url('/flightops/map/') }}">Flight Map</a></li>
        <li><div class="divider"></div></li>
        <li><a href="{{ url('/flightops/freeflight') }}">Free Flight</a></li>
        <li><a href="{{ url('/flightops/schedule') }}">Schedule</a></li>
        <li><a href="#filePIREP">File PIREP</a></li>

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-image" style="height:100px; overflow: hidden;">
                    <img src="http://flyjetconnect.org/img/712_1.png">
                    <span class="card-title">Bids: {{ \App\Bid::where('user_id', Auth::user()->id)->count() }}</span>
                </div>
                <div class="card-action">
                    <a href="{{ url('/flightops/bids') }}">View Bids</a>
                </div>
            </div>
        </div>
        <div class="col s12">
            <div class="card">
                <div class="card-image" style="height:100px; overflow: hidden;">
                    <img src="https://raw.githubusercontent.com/CardinalHorizon/VAOS/master/public/img/login.png">
                    <span class="card-title">Flights: {{ \App\PIREP::where('user_id', Auth::user()->id)->count() }}</span>
                </div>
                <div class="card-action">
                    <a href="{{ url('/flightops/logbook') }}">View Logbook</a>
                </div>
            </div>
        </div>
    </div>
    <li><div class="divider"></div></li>
    <li><a class="waves-effect" href="{{ url('/flightops/settings') }}">User Settings</a></li>
    @if(Auth::user()->admin)
        <li><a class="waves-effect" href="{{ url('/admin') }}">Admin Panel</a></li>
    @endif
    <li><a class="waves-effect" href="#!">About</a></li>
    <li><a class="waves-effect" onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">Log Out</a></li>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
</ul>
<!-- Main Sidebar Static -->

@yield('content')

<div id="filePIREP" class="modal">
    <form action="{{ url('/flightops/filepirep') }}" method="POST">
        {{csrf_field()}}
        <div class="modal-content">
            <h4>Manually File PIREP</h4>
            <div class="row">
                <div class="col s12">
                    <div class="row">
                        <div class="input-field col s6">
                            <select name="bid">
                                <option value="" disabled selected>Select Bid</option>
                                @foreach(App\Bid::where('user_id', Auth::user()->id)->get() as $bid)
                                    <option value="{{ $bid->id }}">{{ $bid->airline->icao }}{{ $bid->flightnum }}</option>
                                @endforeach
                            </select>
                            <label>Bid</label>
                        </div>
                        <div class="input-field col s12">
                            <input placeholder="Placeholder" name="onlineID" id="onlineID" type="text" class="validate">
                            <label for="onlineID">Online Network ID</label>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button href="#!" type="submit" class="modal-action modal-close waves-effect waves-green btn-flat">File PIREP</button>
        </div>
    </form>
</div>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('/bower_components/materialize/dist/js/materialize.js') }}"></script>
<script>
    $("#slide-button").sideNav();
    $('.modal').modal();
    $('select').material_select();
</script>
</body>
</html>