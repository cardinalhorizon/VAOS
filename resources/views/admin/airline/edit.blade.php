@extends('layouts.admin')

@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ url('admin/airlines') }}">Airlines</a></li>
    <li class="breadcrumb-item active">Edit Airline ({{ $airline->name }})</li>
@endsection

@section('content')
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/airlines/'.$airline->id) }}">
        {{csrf_field()}}
        {{ method_field('PUT') }}

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Basic Information
                </div>
                <div class="card-block">
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Name</label>
                        <div class="col-md-9">
                            <input type="text" id="name" name="name" class="form-control" placeholder="eg. Jet Connect" value="{{ $airline->name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">ICAO</label>
                        <div class="col-md-9">
                            <input type="text" id="icao" name="icao" class="form-control" placeholder="eg. JCO" value="{{ $airline->icao }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">IATA</label>
                        <div class="col-md-9">
                            <input type="text" id="iata" name="iata" class="form-control" placeholder="eg. JC" value="{{ $airline->iata }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">ATC Callsign</label>
                        <div class="col-md-9">
                            <input type="text" id="callsign" name="callsign" class="form-control" placeholder="eg. Jet Connect" value="{{ $airline->callsign }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Airline Logo</label>
                        <div class="col-md-9">
                            <input type="text" id="callsign" name="logo" class="form-control" value="{{ $airline->logo }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Airline Icon</label>
                        <div class="col-md-9">
                            <input type="text" id="callsign" name="widget" class="form-control" value="{{ $airline->widget }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Colors:</label>
                        <div class="col-md-3">
                            <input type="text" id="callsign" name="color_primary" class="form-control" value="{{ $airline->color_primary }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" id="callsign" name="color_secondary" class="form-control" value="{{ $airline->color_secondary }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" id="callsign" name="color_highlight" class="form-control" value="{{ $airline->color_highlight }}">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i>&nbsp; Submit</button>
                    <button type="reset" class="btn btn-danger"><i class="fa fa-ban"></i>&nbsp; Reset</button>
                </div>
            </div>
        </div>
    </form>
@endsection