@foreach($schedule as $s)
    <div class="col-lg-4 col-sm-12">
        <div class="panel panel-primary">
            <div class="panel-body" style="padding: 10px;">
                <div class="flightpanel">

                    <div class="airline-text">{{ $s->airline->icao }}{{ $s->flightnum }}</div>
                    <div class="arrdep">{{ $s->depapt->icao }} - {{ $s->arrapt->icao }}</div>
                    <div class="flightpanel-details">
                        <div>@if($s->aircraft_group == null)
                                Not Assigned
                            @else
                                {{$s->aircraft_group->name}}
                            @endif
                            <i class="fa fa-plane fa-fw"></i>
                        </div>
                    </div>
                    <img id="airline-icon" src="{{ url('/img/AirlineLogos/LogoIcon.png') }}"/>
                </div>
            </div>
            <form action="{{ url('/flightops/bids') }}" method="POST">
                <div class="panel-footer">
                        <span class="pull-left">


                            {{ csrf_field() }}
                            <input hidden name="schedule_id" value="{{ $s->id }}"/>
                            @if($s->aircraft_group == null)
                                <select id="airline" name="airline" class="form-control" size="1">
                                        @foreach($aircraft as $a)
                                        <option value="{{ $a->id }}">{{ $a->name }} - {{ $a->registration }}</option>
                                    @endforeach
                                    </select>
                            @endif
                        </span>
                    <span class="pull-right">
                            <button type="submit" class="btn btn-primary">Quick Bid</button>
                            <a href="#" class="btn btn-info" role="button">Adv. Bid</a>
                        </span>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </div>
@endforeach