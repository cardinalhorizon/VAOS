<?php

namespace App\Services;

use App\Models\Airline;
use App\Http\Requests\StoreAirlineRequest;
use App\Http\Requests\UpdateAirlineRequest;

class AirlineService extends BaseService
{
    /**
     * @return mixed
     */
    public function index()
    {
        return Airline::all();
    }

    /**
     * @param StoreAirlineRequest $request
     *
     * @return mixed
     */
    public function create(StoreAirlineRequest $request)
    {
        return Airline::create($request->all());
    }

    /**
     * @param UpdateAirlineRequest $request
     * @param Airline $airline
     *
     * @return mixed
     */
    public function update(UpdateAirlineRequest $request, Airline $airline)
    {
        return $airline->update($request->all());
    }
}
