<?php

namespace App\Services;

use App\Models\Airline;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAirlineRequest;
use App\Http\Requests\UpdateAirlineRequest;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use Prettus\Repository\Exceptions\RepositoryException;

class AirlineService extends BaseService
{
    protected $airlineRepo;

    public function __construct()
    {
        $this->airlineRepo = app('App\Repositories\AirlineRepository');
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            $this->airlineRepo->pushCriteria(new RequestCriteria($request));

            return $this->airlineRepo->all();
        } catch (RepositoryException $e) {
            return false;
        }
    }

    /**
     * @param StoreAirlineRequest $request
     *
     * @return mixed
     */
    public function create(StoreAirlineRequest $request)
    {
        try {
            return $this->airlineRepo->create($request->all());
        } catch (ValidatorException $e) {
            return false;
        }
    }

    /**
     * @param UpdateAirlineRequest $request
     * @param Airline $airline
     *
     * @return mixed
     */
    public function update(UpdateAirlineRequest $request, Airline $airline)
    {
        try {
            return $this->airlineRepo->update($request->all(), $airline->id);
        } catch (ValidatorException $e) {
            return false;
        }
    }
}
