<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Payment records — never store CVV or full card numbers.
 * Mock: method, last four digits. Stripe: session/intent ids and status only.
 */
class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'amount',
        'payment_method',
        'payment_gateway',
        'card_last_four',
        'payment_status',
        'transaction_id',
        'stripe_checkout_session_id',
        'stripe_payment_intent_id',
        'payment_date',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
