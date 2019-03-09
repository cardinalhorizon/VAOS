@extends('layouts.admin2')

@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ url('admin/schedule') }}">Schedule</a></li>
    <li class="breadcrumb-item active">Edit Route ({{ $schedule->airline->icao . $schedule->flightnum }})</li>
@endsection

@section('content')
    <edit-route route="{{$schedule}}" acfgrp="{{$acfgrps}}"></edit-route>
@endsection
<script>

</script>