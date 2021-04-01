<?php

namespace App\Http\Controllers\AdminAPI;

use App\Classes\VAOS_Aircraft;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AircraftAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = json_decode($request->input('data'));
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
            VAOS_Aircraft::createAircraft($data);
        }
        return response()->json(['status' => 200]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if (VAOS_Aircraft::updateAircraft($data, $id)) {
            return response()->json([], 200);
        } else {
            return response()->json([], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
