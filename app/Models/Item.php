<?php

namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Application;

/**
 * @method static findOrFail($id)
 * @method static create($request)
 * @method static find(mixed $input)
 */
class Item extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class)->latest();
    }

    public function getImageFullPathAttribute(): Application|string|UrlGenerator|null
    {
        return $this->image_path != null ? url("storage/{$this->image_path}") : null;
    }
}
