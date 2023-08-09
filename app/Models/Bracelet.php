<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Bracelet extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'name',
        'group',
        'status',
    ];

    /**
     * Get the order that owns the bracelet
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the customer that owns the bracelet
     */
    public function customer(): HasOneThrough
    {
        return $this->hasOneThrough(Customer::class, Order::class);
    }
}
