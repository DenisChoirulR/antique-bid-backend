<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AutoBidRequest;
use App\Http\Requests\Api\BidRequest;
use App\Http\Resources\AutoBidResource;
use App\Http\Resources\BidResource;
use App\Repositories\BidRepository\Interfaces\BidRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BidController extends Controller
{
    public function __construct(
        protected BidRepositoryInterface $repository
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        return BidResource::collection(
            $this->repository->all(),
        );
    }

    public function store(BidRequest $request): BidResource
    {
        return BidResource::make(
            $this->repository->create($request)
        );
    }

    public function storeAutoBid(AutoBidRequest $request): AutoBidResource
    {
        return AutoBidResource::make(
            $this->repository->storeAutoBid($request)
        );
    }

    public function deleteAutoBid($itemId)
    {
        return $this->repository->deleteAutoBid($itemId);
    }
}
