<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AutoBidResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user->name,
            'bid_alert_percentage' => $this->bid_alert_percentage,
            'max_bid_amount' => $this->max_bid_amount,
            'item' => ItemResource::make($this->whenLoaded('item')),
        ];
    }
}
