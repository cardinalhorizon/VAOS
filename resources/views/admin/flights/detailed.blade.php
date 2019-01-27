@extends('layouts.admin2')

@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a>
    </li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Flight Management</a>
    </li>
    <li class="breadcrumb-item active">{{ $flight->callsign }}</li>
@endsection

@section('content')
<flight-view flight="{{$flight}}"></flight-view>
@endsection
