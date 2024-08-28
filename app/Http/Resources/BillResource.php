<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
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
            'item' => ItemResource::make($this->whenLoaded('item')),
            'user' => UserResource::make($this->whenLoaded('user')),
            'amount' => $this->amount,
            'status' => $this->status->value,
            'payment_due_date' => $this->payment_due_date,
            'created_at' => $this->created_at
        ];
    }
}
