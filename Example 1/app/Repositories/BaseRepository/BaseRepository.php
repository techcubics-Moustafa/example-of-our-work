<?php


namespace App\Repositories\BaseRepository;

use App\Interfaces\BaseRepository\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    public Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    // fetch all data
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->get();
    }

    // store in model
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    // show in model
    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    // update in model
    public function update($id, array $data)
    {
        return $this->model->findOrFail($id)->update($data);
    }

    // delete in model
    public function delete($id)
    {
        return $this->model->findOrFail($id)->delete();
    }

    // delete in model where conditions
    public function deleteWhere($conditions)
    {
        return $this->model->where($conditions)->delete();
    }

    // delete in model
    public function destroy($collect): int
    {
        return $this->model->destroy($collect);
    }

    public function restore($id)
    {
        $model = $this->model->where('id', '=', $id)->withTrashed()->first();
        return $model->restore();
    }

    // Get the associated model
    public function getModel(): Model
    {
        return $this->model;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->model = $model;
    }

    // Eager loaf database relationships
    public function with($relation): \Illuminate\Database\Eloquent\Builder
    {
        return $this->model->with($relation);
    }

    // Eager loaf database whereHas
    public function whereHas($relation, $callback)
    {
        return $this->model->whereHas($relation, $callback);
    }
}
