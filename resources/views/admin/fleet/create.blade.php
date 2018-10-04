@extends('layouts.admin2')

@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ url('admin/fleet') }}">Fleet</a></li>
    <li class="breadcrumb-item active">New Aircraft</li>
@endsection

@section('content')
    <create-aircraft></create-aircraft>
@endsection

@section('js')
    <script src="{{ asset('/resources/admin/js/MasterData.js') }}"></script>
@endsection