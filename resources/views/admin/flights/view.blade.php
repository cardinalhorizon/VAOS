@extends('layouts.admin2')
@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a>
    </li>
    <li class="breadcrumb-item active">Flight Management</li>
@endsection

@section('content')
    @if(session('aircraft_created'))
        <div class="alert alert-success"><b>Aircraft Successfully Added:</b> {{session('aircraft_created')}}</div>
    @endif
    <h1>Active Flights
        <div class="btn-group float-right" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-primary"><i class="fa fa-user-plus"></i></button>
            <button type="button" class="btn btn-warning"><i class="fa fa-users"></i></button>
        </div>
    </h1>
    <div class="row">
        <div class="col-lg-4 hidden-sm-down">
            <div class="card">
                <div class="card-body p-0 d-flex align-items-center">
                    <i class="fa fa-bookmark bg-primary p-4 font-2xl mr-3"></i>
                    <div>
                        <div class="text-value-sm text-primary">$1.999,50</div>
                        <div class="text-muted text-uppercase font-weight-bold small">Booked</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 hidden-sm-down">
            <div class="card">
                <div class="card-body p-0 d-flex align-items-center">
                    <i class="fa fa-plane bg-primary p-4 font-2xl mr-3"></i>
                    <div>
                        <div class="text-value-sm text-primary">$1.999,50</div>
                        <div class="text-muted text-uppercase font-weight-bold small">Active</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 hidden-sm-down">
            <div class="card">
                <div class="card-body p-0 d-flex align-items-center">
                    <i class="fa fa-book bg-primary p-4 font-2xl mr-3"></i>
                    <div>
                        <div class="text-value-sm text-primary">$1.999,50</div>
                        <div class="text-muted text-uppercase font-weight-bold small">Logged</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <active-flight-list></active-flight-list>
    <h1>Logged Flights</h1>
@endsection
@section('js')

@endsection