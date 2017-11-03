@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <table>
                <thead>
                <tr>
                    <th>ICAO</th>
                    <th>IATA</th>
                    <th>Name</th>
                    <th>Callsign</th>
                </tr>
                </thead>

                <tbody>
                @foreach($airlines as $airline)
                <tr>
                    <td>{{$airline->icao}}</td>
                    <td>{{$airline->iata}}</td>
                    <td>{{$airline->name}}</td>
                    <td>{{$airline->callsign}}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection