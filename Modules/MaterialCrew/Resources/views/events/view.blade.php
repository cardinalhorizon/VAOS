@extends('materialcrew::layouts.crewops')
@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link href="https://raw.githubusercontent.com/cesarve77/select2-materialize/master/select2-materialize.css" type="text/css" rel="stylesheet" >
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col s10">
                @if(!$events == '[]')
                    {{ $events->appends(\Illuminate\Support\Facades\Input::except('page'))->links('vendor.pagination.material') }}
                @endif
            </div>
            <div class="col s2">

            </div>
            @foreach($events as $e)
                <div class="col m6 s12">
                    <div class="card sticky-action">
                        <div class="card-image waves-effect waves-block waves-light">
                            <img class="activator" src="{!! $e->banner_url !!}">
                        </div>
                        <div class="card-content">
                            <span class="card-title activator grey-text text-darken-4">{{ $e->name }}<i class="material-icons right">more_vert</i></span>
                            <p>{{ $e->airline->name }}</p>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4">{{ $e->name }}<i class="material-icons right">close</i></span>
                            <p>Here is some more information about this product that is only revealed once clicked on.</p>
                        </div>
                        <div class="card-action">
                            <a class="btn blue" href="{{ route('flightops.events.show', ['url_slug' => $e->url_slug]) }}">More Information</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection