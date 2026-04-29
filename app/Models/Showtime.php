<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Showtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'theatre_id',
        'showtime',
        'ticket_price',
        'available_seats',
        'language',
        'format'
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function theatre()
    {
        return $this->belongsTo(Theatre::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
