<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OverviewController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $items = Item::whereHas('bids', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return ItemResource::collection($items->load('winner'));
    }
}
