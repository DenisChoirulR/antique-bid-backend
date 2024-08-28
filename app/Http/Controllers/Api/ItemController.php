<?php

namespace App\Http\Controllers\Api;

use App\Events\ItemUpdate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ItemRequest;
use App\Http\Resources\AutoBidResource;
use App\Http\Resources\BillResource;
use App\Http\Resources\ItemResource;
use App\Repositories\ItemRepository\Interfaces\ItemRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ItemController extends Controller
{
    public function __construct(
        protected ItemRepositoryInterface $repository
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        return ItemResource::collection(
            $this->repository->all($request),
        );
    }

    public function show($id): ItemResource
    {
        return new ItemResource(
            $this->repository->find($id)->load('bids', 'winner'),
        );
    }

    public function store(ItemRequest $request): ItemResource
    {
        return ItemResource::make(
            $this->repository->create($request)
        );
    }

    public function update($id, ItemRequest $request): ItemResource
    {
        $item = $this->repository->update($id, $request);
        event(new ItemUpdate('Item Updated'));

        return ItemResource::make($item);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    public function getBill($id)
    {
        return new BillResource(
            $this->repository->getBill($id)->load('item', 'user'),
        );
    }
}
