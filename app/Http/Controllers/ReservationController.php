<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Show;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = auth()->user()->reservations()
            ->with('show.movie')
            ->latest()
            ->get();

        return view('reservations.index', compact('reservations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'show_id' => 'required|exists:shows,id',
            'seats' => 'required|integer|min:1|max:10',
            'seat_numbers' => 'nullable|array',
            'snacks' => 'nullable|array',
        ]);

        $show = Show::findOrFail($validated['show_id']);

        // Check if seats are available
        if (!$show->hasAvailableSeats($validated['seats'])) {
            return back()->with('error', 'Niet genoeg plaatsen beschikbaar');
        }

        // Calculate total price
        $totalPrice = $show->price * $validated['seats'];
        
        // Add snacks price if any
        if (isset($validated['snacks']) && is_array($validated['snacks'])) {
            foreach ($validated['snacks'] as $snack) {
                $totalPrice += $snack['price'] * $snack['quantity'];
            }
        }

        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'show_id' => $validated['show_id'],
            'seats' => $validated['seats'],
            'seat_numbers' => $validated['seat_numbers'] ?? null,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'ticket_code' => Reservation::generateTicketCode(),
            'snacks' => $validated['snacks'] ?? null,
        ]);

        return redirect()->route('reservations.show', $reservation->id)
            ->with('success', 'Reservering succesvol aangemaakt!');
    }

    public function show($id)
    {
        $reservation = Reservation::with('show.movie')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        // Generate QR code for the ticket
        $qrCode = base64_encode(QrCode::format('svg')->size(200)->generate($reservation->ticket_code));

        return view('reservations.show', compact('reservation', 'qrCode'));
    }

    public function addSnacks(Request $request, $id)
    {
        $reservation = Reservation::where('user_id', auth()->id())->findOrFail($id);

        if ($reservation->isPaid()) {
            return back()->with('error', 'Kan geen snacks toevoegen aan een betaalde reservering');
        }

        $validated = $request->validate([
            'snacks' => 'required|array',
        ]);

        $additionalPrice = 0;
        foreach ($validated['snacks'] as $snack) {
            $additionalPrice += $snack['price'] * $snack['quantity'];
        }

        $reservation->update([
            'snacks' => $validated['snacks'],
            'total_price' => $reservation->total_price + $additionalPrice,
        ]);

        return back()->with('success', 'Snacks toegevoegd aan reservering');
    }

    public function pay(Request $request, $id)
    {
        $reservation = Reservation::where('user_id', auth()->id())->findOrFail($id);

        if ($reservation->isPaid()) {
            return back()->with('error', 'Reservering is al betaald');
        }

        $validated = $request->validate([
            'payment_method' => 'required|string|in:ideal,creditcard,paypal',
        ]);

        $reservation->markAsPaid($validated['payment_method']);

        // In a real application, you would integrate with a payment gateway here
        // For now, we'll just mark it as paid

        return redirect()->route('reservations.show', $reservation->id)
            ->with('success', 'Betaling succesvol! Je ticket is nu actief.');
    }

    public function download($id)
    {
        $reservation = Reservation::with('show.movie')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        if (!$reservation->isPaid()) {
            return back()->with('error', 'Alleen betaalde tickets kunnen worden gedownload');
        }

        // Generate QR code
        $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($reservation->ticket_code));

        $pdf = Pdf::loadView('reservations.ticket-pdf', compact('reservation', 'qrCode'));
        
        return $pdf->download('ticket-' . $reservation->ticket_code . '.pdf');
    }
}

