<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'amount',
        'payment_method',
        'card_number',
        'card_holder',
        'card_expiry',
        'payment_status',
        'transaction_id',
        'payment_date',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
