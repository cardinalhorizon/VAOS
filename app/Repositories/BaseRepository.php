<?php

namespace App\Repositories;

use Illuminate\Validation\Validator;

abstract class BaseRepository extends \Prettus\Repository\Eloquent\BaseRepository
{
    /**
     * @param $id
     * @param array $columns
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return mixed
     */
    public function findWithoutFail($id, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->find($id, $columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * @param $values
     *
     * @return bool
     */
    public function validate($values)
    {
        $validator = Validator::make(
            $values,
            $this->model()->rules
        );
        if ($validator->fails()) {
            return $validator->messages();
        }

        return true;
    }
}
