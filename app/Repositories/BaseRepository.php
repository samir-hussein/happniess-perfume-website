<?php

namespace App\Repositories;

use App\Interfaces\IRepository;
use App\Exceptions\GeneralException;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements IRepository
{
    public function __construct(protected Model $model) {}

    public function getById(int $id)
    {
        $model = $this->model::find($id);

        if (!$model) {
            throw new GeneralException("Model not found");
        }

        return $model;
    }

    public function create(array $data)
    {
        return $this->model::create($data);
    }

    public function update(int $id, array $data)
    {
        try {
            return $this->model::find($id)->update($data);
        } catch (\Throwable $th) {
            throw new GeneralException("Failed to update resource with id $id");
        }
    }

    public function delete(int $id)
    {
        try {
            $this->model::find($id)->delete();
            return true;
        } catch (\Throwable $th) {
            throw new GeneralException("Failed to delete resource with id $id");
        }
    }

    public function getAll()
    {
        return $this->model::latest("id")->get();
    }
}
