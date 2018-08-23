@extends('materialcrew::layouts.crewops')
@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link href="https://raw.githubusercontent.com/cesarve77/select2-materialize/master/select2-materialize.css" type="text/css" rel="stylesheet" >
@endsection
@section('content')
    <div class="z-depth-2" style="position: relative; width: 100%; height: 300px; overflow: hidden; background: url(http://i.imgur.com/3UZDNCM.png);     background-repeat: no-repeat;
            background-position: center;
            background-size: cover;">
        <div style="height: 100%; background: linear-gradient(rgba(255,0,0,0), rgba(255,0,0,0), rgba(69,69,69,0.9))">
        </div>
        <h3 class="white-text" style="position: absolute; bottom: 0; left: 2rem;">Airlines</h3>
    </div>
    <div class="container">
        <div class="row">
            @foreach($airlines as $a)
                <div class="col m12 s12">
                    <div class="card">
                        <div class="card-image" style="background-color: grey">
                            <img src="{!! $a->logo !!}">
                        </div>
                        <div class="card-stacked">
                            <div class="card-content">
                                <h4>{{ $a->name }}</h4>
                            </div>
                            <form action="{{ route('flightops.airlines.join', ['id' => $a->id]) }}" method="POST">
                                {{ csrf_field() }}
                                <input hidden name="schedule_id" value="{{ $a->id }}"/>
                                <div class="card-action">
                                    @if(Auth::guest())
                                        <b>PLEASE LOGIN TO JOIN AIRLINE</b>
                                    @else
                                        @if($a->inAirline)
                                            <a href="{{ route('flightops.airlines.view', ['id' => $a->id]) }}" class="btn blue">Info</a>
                                        @else
                                            <a href="{{ route('flightops.airlines.view', ['id' => $a->id]) }}" class="btn blue">Info</a>
                                            <button type="submit" class="btn green">Join</button>
                                        @endif
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#airline').select2();
            $('#depapt').select2();
            $('#arrapt').select2();
            $('#aircraft').select2();
        });
    </script>
@endsection