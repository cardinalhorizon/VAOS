@extends('layouts.admin')

@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="#">Admin</a>
    </li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <div class="col-xs-6 col-lg-3">
        <div class="card">
            <div class="card-block p-a-1 clearfix">
                <i class="fa fa-users bg-primary p-a-1 font-2xl m-r-1 pull-left"></i>
                <div class="h5 text-primary m-b-0 m-t-h">{{ \App\User::where('status', 1)->count() }}</div>
                <div class="text-muted text-uppercase font-weight-bold font-xs">Active Pilots</div>
            </div>
            <div class="card-footer p-x-1 p-y-h">
                <a class="font-weight-bold font-xs btn-block text-muted" href="#">View More <i class="fa fa-angle-right pull-right font-lg"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xs-6 col-lg-3">
        <div class="card">
            <div class="card-block p-a-1 clearfix">
                <i class="fa fa-book bg-success p-a-1 font-2xl m-r-1 pull-left"></i>
                <div class="h5 text-success m-b-0 m-t-h">{{ \App\PIREP::all()->count() }}</div>
                <div class="text-muted text-uppercase font-weight-bold font-xs">Logged Flights</div>
            </div>
            <div class="card-footer p-x-1 p-y-h">
                <a class="font-weight-bold font-xs btn-block text-muted" href="#">View More <i class="fa fa-angle-right pull-right font-lg"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xs-6 col-lg-3">
        <div class="card">
            <div class="card-block p-a-1 clearfix">
                <i class="fa fa-users bg-primary p-a-1 font-2xl m-r-1 pull-left"></i>
                <div class="h5 text-primary m-b-0 m-t-h">{{ \App\User::where('status', 1)->count() }}</div>
                <div class="text-muted text-uppercase font-weight-bold font-xs">Active Pilots</div>
            </div>
            <div class="card-footer p-x-1 p-y-h">
                <a class="font-weight-bold font-xs btn-block text-muted" href="#">View More <i class="fa fa-angle-right pull-right font-lg"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xs-6 col-lg-3">
        <div class="card">
            <div class="card-block p-a-1 clearfix">
                <i class="fa fa-users bg-primary p-a-1 font-2xl m-r-1 pull-left"></i>
                <div class="h5 text-primary m-b-0 m-t-h">{{ \App\User::where('status', 1)->count() }}</div>
                <div class="text-muted text-uppercase font-weight-bold font-xs">Active Pilots</div>
            </div>
            <div class="card-footer p-x-1 p-y-h">
                <a class="font-weight-bold font-xs btn-block text-muted" href="#">View More <i class="fa fa-angle-right pull-right font-lg"></i></a>
            </div>
        </div>
    </div>
@endsection