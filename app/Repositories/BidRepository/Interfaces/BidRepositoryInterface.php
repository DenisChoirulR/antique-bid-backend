<?php

namespace App\Repositories\BidRepository\Interfaces;

interface BidRepositoryInterface
{
    public function all();
    public function create($request);
    public function storeAutoBid($request);
    public function deleteAutoBid($id);
}
