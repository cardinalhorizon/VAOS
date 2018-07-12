@extends('layouts.admin')

@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ url('admin/hubs') }}">Hubs</a></li>
    <li class="breadcrumb-item active">New Hub</li>
@endsection

@section('content')
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/hubs') }}">
        {{csrf_field()}}

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Basic Information
                </div>
                <div class="card-block">
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="select">Airline</label>
                        <div class="col-md-9">
                            <select id="airline" name="airline" class="form-control" size="1">
                                @if($airlines == "[]")
                                    <option value="0">No Airlines Found</option>
                                @else
                                    @foreach($airlines as $a)
                                        <option value="{{ $a->icao }}">{{ $a->icao }} - {{ $a->name }}</option>
                                    @endforeach
                                @endif
                            </select>

                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">ICAO</label>
                        <div class="col-md-9">
                            <input type="text" id="icao" name="icao" list="apt" class="form-control" placeholder="eg. KDTW">
                            <datalist id="apt">
                                @foreach(App\Models\Airport::all() as $a)
                                    <option value="{{ $a->icao }}">{{ $a->name }}</option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="card">
                <div class="card-block">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i>&nbsp; Submit</button>
                    <button type="reset" class="btn btn-danger"><i class="fa fa-ban"></i>&nbsp; Reset</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('js')
    <script src="{{ asset('/resources/admin/js/MasterData.js') }}"></script>
@endsection