<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Mock payment records — never store CVV or full card numbers.
 * Only safe metadata: method, last four digits, transaction id, and status.
 */
class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'amount',
        'payment_method',
        'card_last_four',
        'payment_status',
        'transaction_id',
        'payment_date',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
