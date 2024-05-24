<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

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
        'congregation',
    ];

    /**
     * Generate a short registration ID on creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            $registration->registration_id = Str::upper(uniqid('REG' . Carbon::now()->format('y') . '-'));
        });
    }

    public function checkin(): void
    {
        if ($this->checkedin_at !== null) {
            return;
        }

        $this->checkedin_at = Carbon::now();
        $this->save();
    }

    public function generateQrCode(): void
    {
        // Check if the qr code already exists
        if (Storage::disk('public')->exists('qrcodes/' . $this->registration_id . '.png')) {
            return;
        }

        // generate a qr code and save it to the public directory
        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
            ->size(250)
            ->margin(2)
            ->generate(route('registrations.checkin', $this));
        $qrCodePath = 'qrcodes/' . $this->registration_id . '.png';
        Storage::disk('public')->put($qrCodePath, $qrCode);
    }

    public function getQrCode(): string
    {
        $this->generateQrCode();

        // return Storage::url('qrcodes/' . $this->registration_id . '.png');

        // return app url + qr code path
        return config('app.url') . '/storage/qrcodes/' . $this->registration_id . '.png';
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(EmailNotification::class, 'notifyable');
    }
}
