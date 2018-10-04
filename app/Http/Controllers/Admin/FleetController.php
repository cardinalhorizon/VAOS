<?php

namespace App\Http\Controllers\Admin;

use App\Models\Airline;
use App\Models\Aircraft;
use Illuminate\Http\Request;
use App\Models\AircraftGroup;
use App\Classes\VAOS_Aircraft;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FleetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($agrp)
    {
        if ($agrp === 'all')
        {
            $groups = AircraftGroup::with('aircraft', 'aircraft.airline', 'aircraft.location', 'aircraft.hub')->get();
        }
        else
        {
            $groups = AircraftGroup::where('airline_id', $agrp)->with('aircraft', 'aircraft.airline', 'aircraft.location', 'aircraft.hub', 'aircraft.hub.airport')->get();
        }
        //return $fleet;
        return view('admin.fleet.view', ['groups' => json_encode($groups)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($agrp)
    {
        if ($agrp === 'all')
        {
            $airlines  = Airline::all();
            $acfgroups = AircraftGroup::where('userdefined', true)->get();
        }
        else
        {
            $airlines  = Airline::find($agrp);
            $acfgroups = AircraftGroup::where(['userdefined' => true, 'airline_id' => $agrp])->get();
        }
        return view('admin.fleet.create', ['airlines' => $airlines, 'acfgroups' => $acfgroups]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store($agrp, Request $request)
    {
        $input = json_decode($request->input('data'));
        //dd($input->aircraftData->icao);
        $data                 = [];
        $data['icao']         = $input->aircraftData->icao;
        $data['name']         = $input->aircraftData->name;
        $data['manufacturer'] = $input->aircraftData->manufacturer;
        $data['status'] = 1;
        $data['airline'] = $input->airline->icao;
        $res = array();
        foreach ($input->registrations as $reg)
        {
            $data['registration'] = $reg->registration;
            $data['hub'] = $reg->hub_id;
            if (VAOS_Aircraft::createAircraft($data)) {
                $res[] = $data['registration'];
            }
        }
        $out = "";

        foreach ($res as $a)
        {
            $out .= $a.", ";
        }
        substr($out, 0, -2);
        $request->session()->flash('aircraft_created', $out);
        return redirect()->route('admin.fleet.index', ['agrp' => $agrp]);
        //dd($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('admin/fleet');
    }
    public function apiGet($id)
    {
        if ($id == 0)
        {
            AircraftGroup::with('aircraft', 'airline')->get();
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $aircraft = Aircraft::findOrFail($id);

        $airlines  = Airline::all();
        $acfgroups = AircraftGroup::where('userdefined', true)->get();

        return view('admin.fleet.edit', ['aircraft' => $aircraft, 'airlines' => $airlines, 'acfgroups' => $acfgroups]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data                 = [];
        $data['icao']         = $request->input('icao');
        $data['name']         = $request->input('name');
        $data['manufacturer'] = $request->input('manufacturer');
        $data['registration'] = $request->input('registration');
        $data['range']        = $request->input('range');
        $data['maxpax']       = $request->input('maxpax');
        $data['maxgw']        = $request->input('maxgw');

        if ($request->input('status') == 1) {
            $data['status'] = $request->input('status');
        } else {
            $data['status'] = 0;
        }
        $data['airline'] = $request->input('airline');
        $data['hub']     = null;
        $data['group']   = null;
        //dd($data);

        if (VAOS_Aircraft::updateAircraft($data, $id)) {
            $request->session()->flash('aircraft_updated', true);

            return redirect('admin/fleet');
        } else {
            return response(false);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
