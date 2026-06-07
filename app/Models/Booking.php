<?php

namespace App\Models;

use App\Support\BookingStatus;
use Illuminate\Database\Eloquent\Builder;
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

    public function cancellation()
    {
        return $this->hasOne(Cancellation::class);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWithShowtimeDetails(Builder $query): Builder
    {
        return $query->with('showtime.movie', 'showtime.theatre');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->whereIn('status', [
                BookingStatus::PENDING,
                BookingStatus::CONFIRMED,
                BookingStatus::CANCELLATION_REQUESTED,
            ])
            ->whereHas('showtime', fn (Builder $showtimeQuery) => $showtimeQuery->where('showtime', '>=', now()));
    }

    public function scopePast(Builder $query): Builder
    {
        return $query
            ->whereIn('status', [
                BookingStatus::PENDING,
                BookingStatus::CONFIRMED,
                BookingStatus::CANCELLATION_REQUESTED,
            ])
            ->whereHas('showtime', fn (Builder $showtimeQuery) => $showtimeQuery->where('showtime', '<', now()));
    }

    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', BookingStatus::CANCELLED);
    }

    public function scopeCancellationRequested(Builder $query): Builder
    {
        return $query->where('status', BookingStatus::CANCELLATION_REQUESTED);
    }
}
