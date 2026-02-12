<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function scan()
    {
        return view('admin.scan');
    }

    public function verify($ticketCode)
    {
        $reservation = Reservation::where('ticket_code', $ticketCode)
            ->with(['show.movie', 'user'])
            ->first();

        if (!$reservation) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket niet gevonden',
            ], 404);
        }

        if ($reservation->isCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket is geannuleerd',
                'reservation' => $reservation,
            ], 400);
        }

        if (!$reservation->isPaid()) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket is niet betaald',
                'reservation' => $reservation,
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ticket is geldig!',
            'reservation' => [
                'id' => $reservation->id,
                'ticket_code' => $reservation->ticket_code,
                'user_name' => $reservation->user->name,
                'movie_title' => $reservation->show->movie->title,
                'show_date' => $reservation->show->show_date->format('d-m-Y'),
                'show_time' => $reservation->show->show_time,
                'seats' => $reservation->seats,
                'seat_numbers' => $reservation->seat_numbers,
                'snacks' => $reservation->snacks_list,
            ],
        ]);
    }
}
