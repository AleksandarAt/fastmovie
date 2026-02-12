<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use App\Models\Show;
use App\Models\Reservation;

class ReservationController extends Controller
{

    public function store(Show $show)
    {
        if ($show->reservations()->count() >= $show->total_seats) {
            return back()->with('error', 'Uitverkocht');
        }

        $seat = 'A' . rand(1, 50);

        $qr = uniqid();

        Reservation::create([
            'show_id' => $show->id,
            'user_id' => auth()->id(),
            'seat_number' => $seat,
            'qr_code' => $qr
        ]);

        return redirect('/my-reservations');
    }
}
