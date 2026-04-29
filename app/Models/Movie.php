<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'rating',
        'duration',
        'genre',
        'release_date',
        'trailer_url',
        'cast',
        'crew',
        'formats',
        'languages',
        'is_active'
    ];

    protected $casts = [
        'cast' => 'array',
        'crew' => 'array',
        'release_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}
