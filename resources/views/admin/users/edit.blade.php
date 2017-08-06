@extends('layouts.admin')

@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ url('admin/users') }}">Users</a></li>
    <li class="breadcrumb-item active">Edit User</li>
@endsection

@section('content')
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/schedule') }}">
        {{csrf_field()}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Basic Information
                </div>
                <div class="card-block">
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">First Name</label>
                        <div class="col-md-9">
                            <input type="text" id="flightnum" name="first_name" class="form-control" placeholder="eg. John">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Last Name</label>
                        <div class="col-md-9">
                            <input type="text" id="flightnum" name="last_name" class="form-control" placeholder="eg. Doe">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Pilot ID</label>
                        <div class="col-md-9">
                            <input type="text" id="depicao" name="pilotid" class="form-control" placeholder="eg. KLAX">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Pilot ID</label>
                        <div class="col-md-9">
                            <input type="text" id="depicao" name="username" class="form-control" placeholder="eg. KLAX">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">VATSIM ID:</label>
                        <div class="col-md-9">
                            <input type="text" id="depicao" name="vatsim" class="form-control" placeholder="eg. KLAX">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">IVAO ID:</label>
                        <div class="col-md-9">
                            <input type="text" id="depicao" name="ivao" class="form-control" placeholder="eg. KLAX">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Avatar URL:</label>
                        <div class="col-md-9">
                            <input type="text" id="depicao" name="avatar_url" class="form-control" placeholder="eg. KLAX">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Cover URL:</label>
                        <div class="col-md-9">
                            <input type="text" id="depicao" name="cover_url" class="form-control" placeholder="eg. KLAX">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i>&nbsp; Submit</button>
                    <button type="reset" class="btn btn-danger"><i class="fa fa-ban"></i>&nbsp; Reset</button>
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