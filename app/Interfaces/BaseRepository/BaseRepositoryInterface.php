<?php


namespace App\Interfaces\BaseRepository;


interface BaseRepositoryInterface
{
    public function index();

    public function create(array $data);

    public function findById($id);

    public function update($id,array $data);

    public function delete($id);

    public function deleteWhere($conditions);

    public function destroy($collect);

    public function restore($id);

    public function getModel();

    public function setModel($model);

    public function with($relation);

    public function whereHas($relation,$callback);
}
