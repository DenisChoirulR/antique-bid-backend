<?php

namespace App\Repositories\ItemRepository;

use App\Models\AutoBid;
use App\Models\Item;
use App\Repositories\ItemRepository\Interfaces\ItemRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ItemRepository implements ItemRepositoryInterface
{
    public function all($request): LengthAwarePaginator
    {
        $query = Item::with('bids');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('sort_by') && $request->has('sort_order')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->sort_order;
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query->paginate(10);
    }

    public function find($id): Item
    {
        return Item::findOrFail($id);
    }

    public function create($request)
    {
        $uploadedImage = $request->file('image');
        $imagePath = $uploadedImage->store('items', 'public');

        return Item::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'image_path' => $imagePath,
            'starting_price' => $request->starting_price,
            'current_price' => $request->starting_price,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
    }

    public function update($id, $request)
    {
        $item = Item::findOrFail($id);
        if ($request->has('name')) {
            $item->name = $request->name;
        }
        if ($request->has('slug')) {
            $item->slug = $request->slug;
        }
        if ($request->has('description')) {
            $item->description = $request->description;
        }
        if ($request->has('image')) {
            $uploadedImage = $request->file('image');
            $imagePath = $uploadedImage->store('items', 'public');
            $item->image_path = $imagePath;
        }
        if ($request->has('starting_price')) {
            $item->starting_price = $request->starting_price;
        }
        if ($request->has('start_time')) {
            $item->start_time = $request->start_time;
        }
        if ($request->has('end_time')) {
            $item->end_time = $request->end_time;
        }
        $item->save();

        return $item;
    }

    public function delete($id): Response
    {
        Item::findOrFail($id)->delete();

        return response()->noContent();
    }
}
