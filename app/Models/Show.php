<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Show extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'show_date',
        'show_time',
        'available_seats',
        'price',
    ];

    protected $casts = [
        'show_date' => 'date',
        'price' => 'decimal:2',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Calculate available seats
    public function getAvailableSeatsCountAttribute()
    {
        $reservedSeats = $this->reservations()
            ->where('status', '!=', 'cancelled')
            ->sum('seats');
        
        return $this->available_seats - $reservedSeats;
    }

    public function hasAvailableSeats($requestedSeats = 1)
    {
        return $this->availableSeatsCount >= $requestedSeats;
    }
}
