<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'show_id',
        'seats',
        'seat_numbers',
        'total_price',
        'status',
        'payment_method',
        'ticket_code',
        'snacks',
    ];

    protected $casts = [
        'seat_numbers' => 'array',
        'snacks' => 'array',
        'total_price' => 'decimal:2',
    ];

    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Generate unique ticket code
    public static function generateTicketCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('ticket_code', $code)->exists());
        
        return $code;
    }

    // Mark reservation as paid
    public function markAsPaid($paymentMethod = 'online')
    {
        $this->update([
            'status' => 'paid',
            'payment_method' => $paymentMethod,
        ]);
    }

    // Get formatted snacks list
    public function getSnacksListAttribute()
    {
        if (!$this->snacks) {
            return [];
        }
        
        return $this->snacks;
    }

    // Check if reservation is paid
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    // Check if reservation is cancelled
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }
}
