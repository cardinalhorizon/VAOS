@extends('install.layout')
@section('pageinfo')
<p>This setup will walk you through the appropriate steps required to install VAOS on your web server.
    Please notice that the system is in <b>EARLY ACCESS</b> which means that some features may be incomplete or have bugs. If you find a bug, don't hesitate to reach out to Taylor Broad and the rest of the support
    members through our main website or AVSIM support forum.
</p>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12" style="text-align: center;">
        <h2>To get started, select an option below</h2>
    </div>
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                Fresh Installation
            </div>
            <div class="panel-body">
                <p>If you are a new virtual airline, this will be the option for you. This will set you up on a clean slate
                with everything you will need.</p>
                <a href="{{ url('/setup?mode=settings')}}" role="button" class="btn btn-lg btn-primary btn-block">Start Fresh</a>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                Import Existing Virtual Airline
            </div>
            <div class="panel-body">
                <p>If you already have a virutal airline, you can easily import an existing phpVMS 2 airline. The following
                    will be imported into your VA.
                </p>
                <ul>
                    <li>Schedule</li>
                    <li>Pilots (User Accounts)</li>
                    <li>Aircraft</li>
                    <li>Airports (Updated Through Master Server)</li>
                </ul>
                <p>Expect other VA settings to be importable at a later date.</p>
                <a disabled href="{{ url('/setup?mode=settings')}}" role="button" class="btn btn-lg btn-primary btn-block">Import Existing phpVMS VA</a>
            </div>
        </div>
    </div>
</div>
@endsection