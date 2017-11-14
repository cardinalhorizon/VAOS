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
                <i class="fa fa-align-justify"></i> Schedules Table
            </div>
            <div class="card-block">
                <div class="card-block">

                    @if(session('schedule_created'))
                        <div class="alert alert-success">Route successfully created.</div>
                    @elseif(session('schedule_updated'))
                        <div class="alert alert-success">Route successfully updated.</div>
                    @endif

                    <a href="{{ url('admin/migrate') }}" role="button" class="button btn btn-primary"><i class="fa fa-plus"></i>&nbsp; Run Migrations</a>
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
                @endif
            </div>
        </div>
    </div>
@endsection