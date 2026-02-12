<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Show;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ShowSeeder extends Seeder
{
    public function run(): void
    {
        $movies = Movie::all();
        
        // Time slots throughout the day
        $timeSlots = ['14:00', '17:00', '20:00', '22:30'];
        
        // Prices for different time slots
        $prices = [
            '14:00' => 8.50,
            '17:00' => 9.50,
            '20:00' => 11.50,
            '22:30' => 10.50,
        ];

        foreach ($movies as $movie) {
            // Create shows for the next 14 days
            for ($i = 0; $i < 14; $i++) {
                $date = Carbon::today()->addDays($i);
                
                // Skip some random days to make it realistic
                if (rand(0, 4) == 0) {
                    continue;
                }
                
                // Add 2-3 time slots per day
                $numSlots = rand(2, 3);
                $selectedSlots = array_rand(array_flip($timeSlots), $numSlots);
                
                if (!is_array($selectedSlots)) {
                    $selectedSlots = [$selectedSlots];
                }
                
                foreach ($selectedSlots as $time) {
                    Show::create([
                        'movie_id' => $movie->id,
                        'show_date' => $date,
                        'show_time' => $time,
                        'available_seats' => 50,
                        'price' => $prices[$time],
                    ]);
                }
            }
        }
    }
}
