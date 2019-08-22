@extends('layouts.admin2')

@section('content')
    <div>
        <h3>Current Airports: {{ Airport::all()->count() }}</h3>
    </div>
@endsection