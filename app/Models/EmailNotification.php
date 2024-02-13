<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EmailNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'notifyable_id',
        'notifyable_type',
        'notification_type',
    ];

    public function notifyable()
    {
        return $this->morphTo();
    }
}
