<!doctype html>
<head>
    <title>Importing Virtual Airline Install</title>
    <link href="{{URL::asset('css/bootstrap.min.css')}}" rel="stylesheet">
</head>
<body style="background: url(https://i.imgur.com/qOXmrci.png) no-repeat fixed;
            background-size: cover;
            width: 100%;
            height: 100%;
            padding: 0;
            position: relative;">
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <img class="card-img-top" src="..." alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Migrating phpVMS Data</h5>
                    <div><b>Phase: </b><span id="phaseID">Test</span></div>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" id="phaseBar" style="width: 30%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div><b>Importing: </b><span id="itemID">Test</span></div>
                    <div class="progress">
                        <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" id="itemBar" style="width: 10%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="{{URL::asset('js/bootstrap.min.css')}}"></script>
<script>
    $(function(){

        // Ok time to load the imported file. We stored the file in a cookie so let's grab it.
        const appURL = "{{ config('app.url') }}";
        var airlineDatabase;
        $.getJSON( $.cookie("airlineDBFile"), function( data ) {
            // Great, we have the file we need. Now time to start the upload process. First, let's get the users out of our way.

            /**
             * Pilots Section
             */
            $("#phaseID").text('Pilots');
            data['pilots'].forEach( function(pilot, id) {

                // We are importing whom?

                $("#itemID").text(pilot['firstname'] + " " + pilot['lastname']);

                // Send it off to the server.
                $.post( appURL + 'api/import/phpvms/user', pilot).done(function( data ) {
                    // If Success, Move the progress bar.
                    if(data.result === 200) {
                        // Find the percent by simple math.
                        var width = id / data['pilots'].count();
                        $("#itemBar").width(width + "%");
                    }
                });
            });

            /**
             * Route Section. Uses Standard API
             */
            $("#phaseID").text('Airlines');
            data['airlines'].forEach( function(airline, id) {

                // We are importing whom?

                $("#itemID").text(airline['code'] + " - " + airline['name']);

                var send = {
                    name: airline['name'],
                    icao: airline['code'],
                    callsign: airline['name']
                };
                // Send it off to the server.
                $.post( appURL + 'api/v1/airline', send).done(function( data ) {
                    // If Success, Move the progress bar.
                    if(data.result === 200) {
                        // Find the percent by simple math.
                        var width = id / data['airlines'].count();
                        $("#itemBar").width(width + "%");
                    }
                });
            });

            /**
             * Route Section. Uses Standard API
             */
            $("#phaseID").text('Schedule');
            data['schedule'].forEach( function(schedule, id) {

                // We are importing whom?

                $("#itemID").text(schedule['code'] + schedule['flightnum']);

                var send = {
                    airline: schedule['code'],
                    flightnum: airline['flightnum'],
                    depapt: airline['name']
                };
                // Send it off to the server.
                $.post( appURL + 'api/v1/schedule', pilot).done(function( data ) {
                    // If Success, Move the progress bar.
                    if(data.result === 200) {
                        // Find the percent by simple math.
                        var width = id / data['pilots'].count();
                        $("#itemBar").width(width + "%");
                    }
                });
            });
        });

    });
</script>
</body>