@extends('layouts.crewops')

@section('content')
    <div class="container">
    <div class="row">
        <div class="col l6 s12">
            <div class="card">
                <form action="" method="post">
                    <div class="card-content">
                        <span class="card-title">Profile Settings</span>
                        @if(count($errors))
                            <div class="alert alert-danger">
                                <strong>The following error(s) occurred:</strong>
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group">
                            <label>Email Address:</label>
                            <input type="text" name="email" value="{{ Auth::user()->email }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>VATSIM ID:</label>
                            <input type="text" name="vatsim" value="{{ Auth::user()->vatsim }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>IVAO ID:</label>
                            <input type="text" name="ivao" value="{{ Auth::user()->ivao }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Cover Photo URL:</label>
                            <input type="text" name="cover_url" value="{{ Auth::user()->cover_url }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Avatar URL:</label>
                            <input type="text" name="avatar_url" value="{{ Auth::user()->avatar_url }}" class="form-control">
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>New Password:</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Confirm Password:</label>
                            <input type="password" name="password2" class="form-control">
                        </div>
                    </div>
                    <div class="card-action">
                        <a href="{{ url('flightops/profile/' . Auth::id()) }}" class="btn btn-default">Cancel</a>
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-success pull-right">Update</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col s12 l6">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col s12">
                            @if(Auth::user()->cover_url === null)
                                <img src="{{ url('/img/cover_default.png') }}" style="width: 100%">
                            @else
                                <img src="{{ Auth::user()->cover_url }}" style="width: 100%">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection