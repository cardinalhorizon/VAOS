<!DOCTYPE html>
<html lang="en">

<head>
    @if(View::exists('allpagescripts'))
        @include('allpagescripts')
    @endif
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('/safari-pinned-tab.svg') }}" color="#1b89a8">
    <meta name="msapplication-TileColor" content="#00aba9">
    <meta name="theme-color" content="#444444">
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Cabin" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="{{ asset('/css/materialcrew.css') }}"  media="screen,projection"/>
    <link rel="stylesheet" href="{{ asset('/css/material_fix.css') }}">
    <!--Let browser know website is optimized for mobile-->
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0"/>-->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
            'baseUrl' => config('app.url')
        ]); ?>
    </script>
    <style>
        body {
            font-family: 'Cabin', 'Raleway', sans-serif;
        }
        nav, main, footer {
            padding-left: 300px;
        }
        @media only screen and (max-width : 992px) {
            nav, main, footer {
                padding-left: 0;
            }
        }
        .sidenav li>a {
             color: white;
         }
        .sidenav li>a>i.material-icons {
            color: #eee;
        }
        .ibox {
            width: 49%;
            height: 125px;
            display: inline-block;
            border-radius: 10px;
            padding: 5px;
            transition: 0.3s;
            background-color: #212121 !important;
        }
        .ibox:hover {
            transition: 0.3s;
            background-color: #61c7ff !important;
        }

        .card {
            border-radius: 10px;
        }
        .card-offset-title {
            padding-left: 2rem;
            font-size: 2.23rem;
            line-height: 2.23rem;
            color: white;
        }
        .brand-primary {
            background-color: #61c7ff;
        }
        .brand-green {
            background-color: #6aff9a;
        }
        .brand-red {
            background-color: #d32637;
        }
        .brand-yellow {
            background-color: #eeff52;
        }
        .brand-primary-outline {
            border: #61c7ff 5px solid;
        }
        .brand-green-outline {
            border: #6aff9a 5px solid;
        }
        .brand-red-outline {
            border: #d32637 5px solid;
        }
        .brand-yellow-outline {
            border: #eeff52 5px solid;
        }
        .btn-outline {
            text-decoration: none;
            color: #fff;
            text-align: center;
            letter-spacing: .5px;
            -webkit-transition: background-color .2s ease-out;
            transition: background-color .2s ease-out;
            cursor: pointer;
        }
        th {
            font-weight: normal;
        }
        table {
            border-color: white;
        }
    </style>
    <title>Crew Operations</title>
    @yield('customcss')
</head>

<body class="grey darken-4" style="position: relative;">
<div class="hide-on-med-and-down" style="position: fixed; z-index: -99; height: 100vh; width: 100vw; background: url('{{ asset('/img/vaos_df_bg-01.svg') }}') black no-repeat center; background-size: cover;"></div>
<div class="navbar-fixed">
<nav class="grey darken-3 z-depth-2 hide-on-large-only">
    <div>
        <div class="nav-wrapper">
            <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <a href="/flightops" class="brand-logo" style="margin-left: 1rem; display: inline-flex; vertical-align: middle; text-overflow: clip;">
                Spark Virtual</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                {{--
                @if(Auth::guest())
                    <li><a class="btn light-blue darken-3" href="{{ url('/login') }}">Login / Register</a></li>
                @else
                    <li><a class="btn blue darken-4" href="{{ url('/flightops') }}">Community Center</a></li>
                @endif
                --}}
            </ul>
        </div>
    </div>
</nav>
</div>
<ul id="slide-out" class="sidenav sidenav-fixed white-text" style="background-color: rgba(25,25,25,.75);">
    <li>
        <div class="user-view" style="height: 176px; padding-top: 50px;">
            <div class="background" style="background: url('https://i.imgur.com/qOXmrci.png') black no-repeat center; background-size: cover;">
                <!--<img src="{{ Auth::user()->cover_url }}" onerror="this.src='http://i.imgur.com/7U0zKFE.png'" style="width:100%;"> -->
            </div>
                <div style="display: inline;">
                    <a href="{{ url('/flightops/profile/'.Auth::user()->id) }}">
                        <img class="circle" style="margin: auto 0;border: white 4px solid;position:absolute;" src="{{ Auth::user()->avatar_url }}" onerror="this.src='http://identicon.org?t={{ Auth::user()->username }}&s=400'">
                        <div style="position: relative;left: 70px;line-height: 30px;color:white; padding-top: 0px;font-size: 18px;">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                        <div style="position: relative;left: 64px; height: 1px; width: 60%; background: white; border: 2px white solid; border-radius: 0 2px 2px 0;"></div>
                        <div style="position: relative;left: 70px;line-height: 30px;color:#61C7FF;font-size: 16px;">{{ Auth::user()->username }}</div>
                    </a>
                </div>
        </div>
    </li>
    <div class="row">
        <div class="col s12">
            <div class="grey darken-3" style="border-radius: 10px;padding: 5px;text-align: center;">
                <div style="font-size: 24px; margin-bottom: .5rem;">Flights</div>
                <?php
                $flight = \App\Models\Flight::where('user_id', Auth::user()->id)->active()->first();
                ?>
                @if(!is_null($flight))
                    <a href="{{route('flightops.flights.show', ['id' => $flight->id])}}"><div class="brand-primary white-text" style="padding: .5rem;margin-bottom: .5rem; border-radius: 10px;">GO TO ACTIVE FLIGHT</div></a>
                    <flight-widget flight="{{ $flight->id }}"></flight-widget>
                @endif
                <a href="{{ route('flightops.flights.index') }}" style="color: white;">
                    <span class="ibox">Upcoming <div style="font-size: 40px;padding-top:10px;">{{ \App\Models\Flight::where('user_id', Auth::user()->id)->filed()->count() }}</div></span>
                </a>
                <a href="{{ url('/flightops/profile/'.Auth::user()->id) }}" style="color: white;">
                    <span class="ibox">Completed <div style="font-size: 40px;padding-top:10px;">{{ \App\Models\Flight::where('user_id', Auth::user()->id)->completed()->count() }}</div></span>
                </a>
            </div>
        </div>
    </div>
    <li><a href="{{ url('/flightops') }}"><i class="material-icons">home</i> Home</a></li>
<!--<li><a href="{{ url('/flightops/community/') }}">Community Center</a></li>-->
    {{--<li><a href="{{ url('/flightops/map/') }}"><i class="material-icons">map</i> Flight Map</a></li>--}}
    {{--<li><a href=""><i class="material-icons">format_list_numbered</i> Tours</a></li>--}}
    <li><a href="{{ url('/flightops/freeflight') }}"><i class="material-icons">airplanemode_active</i> Free Flight</a></li>
    <li><a href="{{ url('/flightops/schedule') }}"><i class="material-icons">schedule</i> Schedule</a></li>
    <li><a href="{{ url('/flightops') }}"><i class="material-icons">event</i> Events</a></li>
    <li><div class="divider"></div></li>
    <li><a class="waves-effect" href="ts3server://flyspark.org"><i class="material-icons">keyboard_voice</i> Teamspeak</a></li>
    <!--<li><a class="waves-effect" href="https://discord.gg/YdqDeZu"><i class="material-icons">textsms</i> Discord Invite</a></li>-->
    <li><a href="https://tfdidesign.com/smartcars/app.php?action=download&airlineid=576&language=en-US"><i class="material-icons">file_download</i> Download smartCARS 2</a></li>
    <li><a href="https://www.dropbox.com/sh/b9f5gu9tmvg8pgi/AAAISD987dkOfmsI_eYzvTgoa?dl=0"><i class="material-icons">file_download</i> Liveries</a></li>
    <li><div class="divider"></div></li>
    <li><a class="waves-effect" href="{{ url('/flightops/settings') }}"><i class="material-icons">settings</i> Settings</a></li>
    @if(Auth::user()->admin)
        <li><a class="waves-effect" href="{{ url('/admin') }}"><i class="material-icons">group</i> Admin Panel</a></li>
    @endif
    <li><a class="waves-effect modal-trigger" href="#aboutModal"><i class="material-icons">info</i> About</a></li>
    <li><a class="waves-effect" onclick="event.preventDefault();
    document.getElementById('logout-form').submit();"><i class="material-icons">exit_to_app</i> Log Out</a></li>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
</ul>
<!-- Main Sidebar Static -->
<main style="" id="app">
    
    <div class="hide-on-med-and-down" style="width: 100%; height: 150px; margin-bottom: 2rem;">
        <img src="{{ asset('/img/MainLogo.svg') }}" style="height: 60%; display: block;
    margin-left: auto;
    margin-right: auto;">
    </div>
@yield('content')
    <div id="aboutModal" class="modal">
        <div class="modal-content">
            <h4>Material Crew</h4>
            <h5>Licensed to {{config('app.name')}}</h5>
            <h6>Credits</h6>
            <ul>
                <li>Taylor Broad <a href="mailto:taylor@cardinalhorizon.com">(taylor@cardinalhorizon.com)</a> - Head Developer</li>
                <li>Derek DePontbriand <a href="mailto:derek@cardinalhorizon.com">(derek@cardinalhorizon.com)</a> - Head Designer</li>
            </ul>
            <a href="https://fsvaos.net">Virtual Aviation Operations System | {{ config('app.version') }}</a>
            <span>&copy; 2015-2018 Cardinal Horizon Inc.</span>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Dismiss</a>
        </div>
    </div>
    <div id="betaDisclaimer" class="modal">
        <div class="modal-content">
            <h4>Welcome to the VAOS 2.0 Beta</h4>
            <h5>Current Release{{config('app.version')}}</h5>
            <p>Please note that you're testing beta software. Expect bugs and display glitches here and there. If you find a bug, please report it along with your Laravel.log file and your hosting configuration to the <a href="https://github.com/FSVAOS/VAOS/issues">GitHub Issues Page</a>
                Thank you for participating in the beta. For updated information regarding what's fixed/broken, please head over to <a href="https://discord.gg/xWFPf4W">our Discord server</a></p>
            <h5>Taylor Broad</h5>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Dismiss</a>
        </div>
    </div>
</main>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('materialize/js/materialize.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/materialcrew.js') }}"></script>
<script>

    $('.sidenav').sidenav();
    $('.modal').modal();
</script>
@yield('js')
</body>
</html>
