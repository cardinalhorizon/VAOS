@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a>
    </li>
    <li class="breadcrumb-item active">Migrations</li>
@endsection
@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Database Migrations
            </div>
            <div class="card-block">
                <div class="card-block">

                    @if(session('success'))
                        <div class="alert alert-success">Database Migrations Successful. Check Batch: {{ $migrations->last()->batch }} for latest changes.</div>
                    @elseif(session('schedule_updated'))
                        <div class="alert alert-success">Route successfully updated.</div>
                    @endif
                    <p> The primary purpose of this page is to track updates and changes to the database throughout the
                    release cycle of VAOS for technical support reasons. Currently there is no way to rollback migrations
                    at this time other than restoring from database backup, however that should be a feature in the future.</p>
                        <p style="color: red;"><b>BEFORE RUNNING NEW MIGRATIONS, BACKUP YOUR ENTIRE DATABASE SCHEMA!!!</b></p>
                    <a href="{{ url('admin/migrate') }}" role="button" class="button btn btn-primary"><i class="fa fa-plus"></i>&nbsp; Run New Migrations</a>
                </div>
                    <table id="table_id" class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Migration Name</th>
                            <th>Batch</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($migrations as $m)
                            <tr>
                                <td>{{$m->id}}</td>
                                <td>{{$m->migration}}</td>
                                <td>{{$m->batch}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
@endsection