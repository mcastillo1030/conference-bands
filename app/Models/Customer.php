<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
    ];

    /**
     * Get the customer's full name
     *
     * @return string
     */
    public function fullName(): string
    {
        return  $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the customer's formatted phone number
     *
     * @return string
     */
    public function phone(): string
    {
        return  empty($this->phone_number) ? '' : '(' . substr($this->phone_number, 0, 3) . ') ' . substr($this->phone_number, 3, 3) . '-' . substr($this->phone_number, 6);
    }

    /**
     * Get the orders that the customer owns
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the bracelets that the customer owns
     */
    public function bracelets(): HasManyThrough
    {
        return $this->hasManyThrough(Bracelet::class, Order::class);
    }
}
