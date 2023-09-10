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
        $formatted = '';

        // if not empty, format
        if (!empty($this->phone_number)) {
            if (substr($this->phone_number, 0, 1) === '(' || substr($this->phone_number, 0, 1) === '+' || str_contains($this->phone_number, '-')) {
                $formatted = $this->phone_number;
            } else {
                $formatted = '(' . substr($this->phone_number, 0, 3) . ') ' . substr($this->phone_number, 3, 3) . '-' . substr($this->phone_number, 6);
            }
        }

        return  $formatted;
    }

    /**
     * Get the customer's phone number formatted for Square API
     *
     * @return string
     */
    public function phoneForSquareApi(): string
    {
        $formatted = '';

        // if not empty, format
        if (!empty($this->phone_number)) {
            // strip out parentheses, dashes, and spaces
            $formatted = preg_replace('/[\(\)\-\s]/', '', $this->phone_number);

            // if string doesn't contain a +1, add it
            if (substr($formatted, 0, 2) !== '+1') {
                $formatted = '+1' . $formatted;
            }

            // return at most 12 characters (including +1)
            $formatted = substr($formatted, 0, 12);
        }

        return  $formatted;
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
