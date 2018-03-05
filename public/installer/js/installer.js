$(function(){

    // Ok time to load the imported file. We stored the file in a cookie so let's grab it.
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
            $.post( '/api/import/phpvms/user', pilot).done(function( data ) {
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
        $("#phaseID").text('Schedule');
        data['schedule'].forEach( function(schedule, id) {

            // We are importing whom?

            $("#itemID").text(schedule['code'] + schedule['flightnum']);

            // Send it off to the server.
            $.post( '/api/import/phpvms/user', pilot).done(function( data ) {
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