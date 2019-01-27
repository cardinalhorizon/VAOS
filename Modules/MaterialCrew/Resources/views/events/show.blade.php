@extends('materialcrew::layouts.crewops')

@section('content')
    <div class="row">
        <div class="col s12">
            <div class="card grey white-text darken-3" style="background: url('{{ $info->cover_url }}'), url('https://i.imgur.com/qOXmrci.png');     background-repeat: no-repeat;
                    background-position: center;
                    background-size: cover;min-height: 250px;">
                <div style="position: absolute; height: 100%; width: 100%; background-color: rgba(25,25,25,.75);z-index: 0"></div>
                <div class="card-content" style="display: flex;justify-content: space-between;position: relative; z-index: 5;">
                    {{ $info->name }}
                </div>
            </div>
        </div>
    </div>
@endsection