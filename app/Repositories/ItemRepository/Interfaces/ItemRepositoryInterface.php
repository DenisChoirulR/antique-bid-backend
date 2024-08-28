<?php

namespace App\Repositories\ItemRepository\Interfaces;

interface ItemRepositoryInterface
{
    public function all($request);
    public function find($id);
    public function create($request);
    public function update($id, $request);
    public function delete($id);
    public function getBill($id);
}
