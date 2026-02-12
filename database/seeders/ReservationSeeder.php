<?php

namespace Database\Seeders;

use App\Models\Show;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        // Get non-admin users
        $users = User::where('is_admin', false)->get();
        
        // Get some shows
        $shows = Show::where('show_date', '>=', today())->take(20)->get();
        
        // Snacks options
        $snacksOptions = [
            [
                ['name' => 'Popcorn medium', 'price' => 4.50, 'quantity' => 1],
                ['name' => 'Cola medium', 'price' => 3.50, 'quantity' => 1],
            ],
            [
                ['name' => 'Popcorn large', 'price' => 5.50, 'quantity' => 1],
                ['name' => 'Cola large', 'price' => 4.50, 'quantity' => 2],
            ],
            [
                ['name' => 'Nachos', 'price' => 5.00, 'quantity' => 1],
                ['name' => 'Sprite medium', 'price' => 3.50, 'quantity' => 1],
            ],
            null, // Some reservations without snacks
            null,
        ];

        foreach ($users as $user) {
            // Create 2-4 reservations per user
            $numReservations = rand(2, 4);
            
            for ($i = 0; $i < $numReservations; $i++) {
                if ($shows->isEmpty()) {
                    break;
                }
                
                $show = $shows->random();
                $seats = rand(1, 4);
                $snacks = $snacksOptions[array_rand($snacksOptions)];
                
                $totalPrice = $show->price * $seats;
                
                // Add snacks price if any
                if ($snacks) {
                    foreach ($snacks as $snack) {
                        $totalPrice += $snack['price'] * $snack['quantity'];
                    }
                }
                
                // Generate seat numbers
                $seatNumbers = [];
                for ($s = 0; $s < $seats; $s++) {
                    $row = chr(65 + rand(0, 9)); // A-J
                    $number = rand(1, 10);
                    $seatNumbers[] = $row . $number;
                }
                
                // Random status
                $status = rand(0, 10) < 8 ? 'paid' : 'pending';
                
                Reservation::create([
                    'user_id' => $user->id,
                    'show_id' => $show->id,
                    'seats' => $seats,
                    'seat_numbers' => $seatNumbers,
                    'total_price' => $totalPrice,
                    'status' => $status,
                    'payment_method' => $status === 'paid' ? ['ideal', 'creditcard', 'paypal'][rand(0, 2)] : null,
                    'ticket_code' => Reservation::generateTicketCode(),
                    'snacks' => $snacks,
                ]);
            }
        }
    }
}
