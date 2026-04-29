<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cancellation extends Model
{
    protected $fillable = [
        'booking_id',
        'original_amount',
        'refund_amount',
        'cancellation_fee',
        'reason',
        'status',
        'cancellation_date',
        'refund_date',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
