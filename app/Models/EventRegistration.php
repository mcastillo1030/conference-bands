<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class EventRegistration extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'customer_id',
        'name',
        'guests',
        'event_date',
        'event_location',
        'checkedin_at',
    ];

    /**
     * Generate a short registration ID on creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            $registration->registration_id = Str::upper( uniqid('REG' . Carbon::now()->format('y') . '-'));
        });
    }

    public function checkin() : void
    {
        if ($this->checkedin_at !== null) {
            return;
        }

        $this->checkedin_at = Carbon::now();
        $this->save();
    }


    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function notifications() : MorphMany
    {
        return $this->morphMany(EmailNotification::class, 'notifyable');
    }
}
