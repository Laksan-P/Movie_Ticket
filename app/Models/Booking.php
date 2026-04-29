<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'showtime_id',
        'number_of_tickets',
        'seats',
        'total_price',
        'status',
        'booking_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
