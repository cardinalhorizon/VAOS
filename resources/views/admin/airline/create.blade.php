@extends('layouts.admin')

@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ url('admin/airlines') }}">Airlines</a></li>
    <li class="breadcrumb-item active">New Airline</li>
@endsection

@section('content')
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/airlines') }}">
        {{csrf_field()}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Basic Information
                </div>
                <div class="card-block">
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Name</label>
                        <div class="col-md-9">
                            <input type="text" id="name" name="name" class="form-control" placeholder="eg. Jet Connect">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">ICAO</label>
                        <div class="col-md-9">
                            <input type="text" id="icao" name="icao" class="form-control" placeholder="eg. JCO">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">IATA</label>
                        <div class="col-md-9">
                            <input type="text" id="iata" name="iata" class="form-control" placeholder="eg. JC">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">ATC Callsign</label>
                        <div class="col-md-9">
                            <input type="text" id="callsign" name="callsign" class="form-control" placeholder="eg. Jet Connect">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-block">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i>&nbsp; Submit</button>
                    <button type="reset" class="btn btn-danger"><i class="fa fa-ban"></i>&nbsp; Reset</button>
                </div>
            </div>
        </div>
    </form>
@endsection