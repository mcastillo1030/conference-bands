<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_type',
    ];

    /**
     * Generate a unique number for the order
     */
    public static function booted(): void
    {
        static::created(function ($order) {
            $today = Carbon::today();
            $order->number = 'rmt' .
                $today->format('y') . '-' .
                $today->format('m') .
                $today->format('d') .
                $order->id;
            $order->save();
        });
    }

    /**
     * Get the public route key name for the model
     */
    public function getRouteKeyName(): string
    {
        return 'number';
    }

    /**
     * Get the customer for the order
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the bracelets for the order
     */
    public function bracelets(): HasMany
    {
        return $this->hasMany(Bracelet::class);
    }

    /**
     * Get the notifications sent for the order.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
