<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Theatre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'total_seats',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}
