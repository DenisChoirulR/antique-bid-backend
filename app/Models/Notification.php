<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @method static create(array $array)
 * @method static where(string $string, $userId)
 */
class Notification extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];


}
