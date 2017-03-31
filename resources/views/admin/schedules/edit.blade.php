@extends('layouts.admin')

@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ url('admin/schedule') }}">Schedule</a></li>
    <li class="breadcrumb-item active">Edit Route ({{ $schedule->airline->icao . $schedule->flightnum }})</li>
@endsection

@section('content')
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/schedule/'.$schedule->id) }}">
        {{csrf_field()}}
        {{ method_field('PUT') }}

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Basic Information
                </div>
                <div class="card-block">
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="select">Airline</label>
                        <div class="col-md-9">
                            <select id="airline" name="airline" class="form-control" size="1">
                                @foreach($airlines as $a)
                                    <option value="{{ $a->icao }}" @if($a->id == $schedule->airline->id) selected="selected" @endif>{{ $a->icao }} - {{ $a->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Flight Number</label>
                        <div class="col-md-9">
                            <input type="text" id="flightnum" name="flightnum" class="form-control" placeholder="eg. 1126" value="{{ $schedule->flightnum }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Departure</label>
                        <div class="col-md-9">
                            <input type="text" id="depicao" name="depicao" class="form-control" placeholder="eg. KLAX" value="{{ $schedule->depapt->icao }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Arrival</label>
                        <div class="col-md-9">
                            <input type="text" id="arricao" name="arricao" class="form-control" placeholder="eg. KSEA" value="{{ $schedule->arrapt->icao }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label"></label>
                        <div class="col-md-9">
                            <div class="checkbox">
                                <label for="checkbox1">
                                    <input type="checkbox" id="enabled" name="enabled" @if($schedule->enabled) checked="checked" @endif value="1"> Enabled
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Route Defaults
                </div>
                <div class="card-block">
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="select">Aircraft Group</label>
                        <div class="col-md-9">
                            <select id="aircraft_group" name="aircraft_group" class="form-control" size="1">
                                <option value="">Not Assigned</option>
                                @foreach($acfgroups as $acf)
                                    <option value="{{ $acf->icao }}" @if(!empty($schedule->aircraft_group->id)) @if($acf->id == $schedule->aircraft_group->id) selected="selected" @endif @endif>{{ $acf->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!--
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Route</label>
                        <div class="col-md-9">
                            <input type="text" id="route" name="route" class="form-control" placeholder="eg. CASTA6 GMN DUCKE BORDY BTG HAWKZ5">
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Cruise Alt</label>
                        <div class="col-md-9">
                            <input type="text" id="route" name="cruise" class="form-control" placeholder="35000">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="select">Type</label>
                        <div class="col-md-9">
                            <select id="type" name="type" class="form-control" size="1">
                                <option value="0">Please select</option>
                                <option value="1" @if($schedule->type == 1) selected="selected" @endif>Scheduled Passenger</option>
                                <option value="2" @if($schedule->type == 2) selected="selected" @endif>Scheduled Cargo</option>
                            </select>
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