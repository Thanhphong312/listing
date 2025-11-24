<?php

namespace Vanguard\Services;

use Illuminate\Database\Eloquent\Model;


abstract class ModelService
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * Set the model
     *
     * @param Model $model
     * @return self
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get the model
     *
     * @return Model
     */
    public function getModel(): Model
    {

        return $this->model;
    }

    public function findById($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function update($id, $payload)
    {
        return $this->model->where('id', $id)->update($payload);
    }

    public function create($payload) {
        return $this->model->fill($payload)->save();
    }
    public function getAll()
    {
        return $this->model->all();
    }
    
}
