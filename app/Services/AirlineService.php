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
     * @throws RepositoryException
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        $this->airlineRepo->pushCriteria(new RequestCriteria($request));

        return $this->airlineRepo->all();
    }

    /**
     * @param StoreAirlineRequest $request
     *
     * @throws ValidatorException
     *
     * @return mixed
     */
    public function create(StoreAirlineRequest $request)
    {
        return $this->airlineRepo->create($request->all());
    }

    /**
     * @param UpdateAirlineRequest $request
     * @param Airline $airline
     *
     * @throws ValidatorException
     *
     * @return mixed
     */
    public function update(UpdateAirlineRequest $request, Airline $airline)
    {
        return $this->airlineRepo->update($request->all(), $airline->id);
    }
}
