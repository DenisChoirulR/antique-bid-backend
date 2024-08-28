<?php

namespace App\Http\Resources;

use App\Models\AutoBid;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $autoBid = AutoBid::where('item_id', $this->id)->where('user_id', Auth::id())->first();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'image_path' => $this->image_path,
            'image_full_path' => $this->ImageFullPath,
            'description' => $this->description,
            'starting_price' => $this->starting_price,
            'current_price' => $this->current_price,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'bids' => BidResource::collection($this->whenLoaded('bids')),
            'auto_bid' => AutoBidResource::make($autoBid),
            'winner' => UserResource::make($this->whenLoaded('winner')),
            'status' => Auth::user()->role->value != 'admin' ? $this->getItemStatus() : null
        ];
    }

    private function getItemStatus()
    {
        $highestBid = $this->bids->first();

        if ($this->end_time->isFuture()) {
            return 'in progress';
        } elseif ($highestBid && $highestBid->user_id == Auth::id()) {
            return 'won';
        } else {
            return 'lost';
        }
    }
}
