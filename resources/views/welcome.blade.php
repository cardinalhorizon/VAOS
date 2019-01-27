<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-119275106-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-119275106-3');
    </script>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Passionate Aviators Revolutionizing Virtual Aviation">
    <meta name="author" content="">
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="{{asset('bower_components/materialize/dist/css/materialize.css')}}"  media="screen,projection"/>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <title>Spark Virtual | Welcome</title>
    <style>
        @media  only screen and (min-width: 601px) {
            nav .nav-wrapper i nav a.button-collapse nav a.button-collapse i {
                height: 80px;
                line-height: 80px;
            }
        }
    </style>
</head>
<body>
<ul id="opsdrop" class="dropdown-content">
    <li><a href="https://github.com/FSVAOS/VAOS/wiki">Documentation</a></li>
    <li><a href="https://github.com/FSVAOS/VAOS/issues">GitHub Issues Tracker</a></li>
    <li><a href="https://discord.gg/xWFPf4W">Discord Invite</a></li>
</ul>
<nav class="red darken-4 z-depth-2">
    <div class="container" style="width: 90%">
        <div class="nav-wrapper">
            <a href="/" class="brand-logo" style="display: inline-flex; vertical-align: middle;">
                Spark Virtual</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="{{ route('login') }}">Login</a></li>

            </ul>
        </div>
    </div>
</nav>
<main>
    <div style="width: 100%; height: 75vh; min-height: 300px; overflow: hidden; background: url('https://i.imgur.com/qOXmrci.png') no-repeat center; position: relative; display: flex;">
        <div class="white-text" style="margin: auto; align-items: center; text-align: center; background-color: rgba(25,25,25,.75); padding: 2rem;">
            <h2>Reigniting Your Virtual Airline Experience</h2>
            <h5>Now Accepting Pre-launch Applications</h5>
            <a class="waves-effect waves-light btn-large green" style="width: 20vw; margin: 10px auto;min-width: min-content;" href="https://goo.gl/forms/wemwUBxLxsYEQAsN2">Apply Today</a>
        </div>
    </div>
    <div class="red darken-4 white-text" style="padding: 30px 0;">
        <div class="container">
            <!-- Start of the Message. Delete everything between these comments. -->
            <h3>Passionate Aviators Revolutionizing Virtual Aviation</h3>
            <p>Spark Virtual Airlines was started by a group of aviation enthusiasts in January 2018. Our fleet
                consists of aircraft ranging from a Bombardier Q400 all the way up to the Boeing 747 Queen of The Skies.
            </p>
            <p>
                Our airline’s focus is on success both in and out of the cockpit, creating a fun and enjoyable community
                for all who come. Furthermore, we intend to continue the use of realism to make the virtual airline
                experience all the more professional and enjoyable.
            </p>
            <p>
                To meet the mission of Spark, the entire website has been built custom from the ground up. As a result,
                we have quite a few unique features you will not find at many other communities. A sampling of features
                includes a fully featured events system, a comprehensive schedule system and custom community features.
            </p>
            <p>A derivative of this system has been publicly released as the Virtual Airline Operations System.
                You can find more information about that project by heading to <a href="http://fsvaos.net">http://fsvaos.net/.</a></p>
                <!-- End Of The Message -->
        </div>
    </div>
</main>
<!-- Modal Structure -->
<div id="modalSetup" class="modal">
    <div class="modal-content">
        <h4>Welcome to VAOS. Kinda</h4>
        <p>Well, welcome to your new system, or we would normally say that. So if you are seeing this message, your webserver
        dependencies should be in line and everything is working. There's just one problem.</p>
        <p><b>YOU STILL NEED TO INSTALL VAOS</b></p>
        <p>Thankfully there's this little button that will now take you to the VAOS web installer. Just follow the instructions.</p>
    </div>
    <div class="modal-footer">
        <a href="{{ url('/setup') }}" class="modal-action modal-close waves-effect waves-green btn-flat">Go to Setup</a>
    </div>
</div>
<footer class="page-footer grey darken-3">
    <div class="footer-copyright grey darken-2">
        <div class="container">
            © 2018 Cardinal Horizon Inc.
            <a class="grey-text text-lighten-4 right" href="#!">VAOS Mainline System Version: {{ config('app.version') }}</a>
        </div>
    </div>
</footer>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="{{asset('/bower_components/materialize/dist/js/materialize.js')}}"></script>
<script>
    $(document).ready(function () {
        @if(!env('VAOS_Setup'))
            $('#modalSetup').modal('open');
        @endif
    });
</script>
</body>