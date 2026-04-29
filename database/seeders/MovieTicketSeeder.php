<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Movie;
use App\Models\Theatre;
use App\Models\Showtime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MovieTicketSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing showtimes to avoid duplicates on re-seed
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Showtime::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@cinebook.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '0712345678',
            ]
        );

        $cast_inception = [
            ['name' => 'Leonardo DiCaprio', 'role' => 'Dom Cobb', 'image' => 'https://image.tmdb.org/t/p/w200/wo2hJSo4MxN7jW3jD58t3B5J2g2.jpg'],
            ['name' => 'Joseph Gordon-Levitt', 'role' => 'Arthur', 'image' => 'https://image.tmdb.org/t/p/w200/961I0V3vV8M8N8N8N8N8N8N8N8.jpg'],
            ['name' => 'Elliot Page', 'role' => 'Ariadne', 'image' => 'https://image.tmdb.org/t/p/w200/vHAtDjtPjM8fJ0XbU1V8P8M8N8N.jpg'],
            ['name' => 'Tom Hardy', 'role' => 'Eames', 'image' => 'https://image.tmdb.org/t/p/w200/d591M13c9uK5l9aF8N3aN053i6n.jpg'],
            ['name' => 'Cillian Murphy', 'role' => 'Robert Fischer', 'image' => 'https://image.tmdb.org/t/p/w200/i89WvW4VnAV89vW9.jpg']
        ];

        $crew_inception = [
            ['name' => 'Christopher Nolan', 'role' => 'Director', 'image' => ''],
            ['name' => 'Emma Thomas', 'role' => 'Producer', 'image' => '']
        ];

        $cast_dark_knight = [
            ['name' => 'Christian Bale', 'role' => 'Bruce Wayne / Batman', 'image' => 'https://image.tmdb.org/t/p/w200/q7S9vAV8N9vW9v.jpg'],
            ['name' => 'Heath Ledger', 'role' => 'The Joker', 'image' => 'https://image.tmdb.org/t/p/w200/nID6Z7Y3X8QxH8A1H8A1H8A1H8A.jpg'],
            ['name' => 'Aaron Eckhart', 'role' => 'Harvey Dent', 'image' => 'https://image.tmdb.org/t/p/w200/9vK8P8mI0V3vV8M8N8N8N8N8N8.jpg'],
            ['name' => 'Michael Caine', 'role' => 'Alfred Pennyworth', 'image' => 'https://image.tmdb.org/t/p/w200/h6S9vAV8N9vW9v.jpg']
        ];

        $cast_interstellar = [
            ['name' => 'Matthew McConaughey', 'role' => 'Cooper', 'image' => 'https://image.tmdb.org/t/p/w200/mX3Lp3162316231623162316231.jpg'],
            ['name' => 'Anne Hathaway', 'role' => 'Brand', 'image' => 'https://image.tmdb.org/t/p/w200/h1B7jY0H7I9psvAVNW9pZ89vYmP.jpg'],
            ['name' => 'Jessica Chastain', 'role' => 'Murph', 'image' => 'https://image.tmdb.org/t/p/w200/9vK8P8mI0V3vV8M8N8N8N8N8N8.jpg'],
            ['name' => 'Casey Affleck', 'role' => 'Tom', 'image' => 'https://image.tmdb.org/t/p/w200/sqmUnfE8i309Y6aNo.jpg']
        ];

        // Movies - EXPANDED
        $movies = [
            [
                'title' => 'Inception', 
                'description' => 'A thief who steals corporate secrets through the use of dream-sharing technology.', 
                'image' => 'assets/images/inception.png', 
                'rating' => '8.8', 
                'duration' => 148, 
                'genre' => 'Sci-Fi', 
                'release_date' => '2010-07-16',
                'trailer_url' => 'https://www.youtube.com/watch?v=YoHD9XEInc0',
                'cast' => $cast_inception,
                'crew' => $crew_inception,
                'formats' => '2D, IMAX',
                'languages' => 'English, Hindi'
            ],
            [
                'title' => 'The Dark Knight',
                'description' => 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham.',
                'image' => 'assets/images/dark_knight.png',
                'rating' => '9.0',
                'duration' => 152,
                'genre' => 'Action',
                'release_date' => '2008-07-18',
                'trailer_url' => 'https://www.youtube.com/watch?v=EXeTwQWrcwY',
                'cast' => $cast_dark_knight,
                'crew' => $crew_inception,
                'formats' => '2D, IMAX, 4DX',
                'languages' => 'English'
            ],
            [
                'title' => 'Interstellar',
                'description' => 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity\'s survival.',
                'image' => 'assets/images/interstellar.png',
                'rating' => '8.7',
                'duration' => 169,
                'genre' => 'Sci-Fi',
                'release_date' => '2014-11-07',
                'trailer_url' => 'https://www.youtube.com/watch?v=zSWdZVtXT7E',
                'cast' => $cast_interstellar,
                'crew' => $crew_inception,
                'formats' => '2D, IMAX',
                'languages' => 'English'
            ]
        ];

        $movieModels = [];
        foreach ($movies as $movieData) {
            $movieModels[] = Movie::updateOrCreate(['title' => $movieData['title']], $movieData);
        }

        // Theatres
        $theatre1 = Theatre::updateOrCreate(['name' => 'Liberty Cinema'], ['location' => 'Colombo 03', 'total_seats' => 100]);
        $theatre2 = Theatre::updateOrCreate(['name' => 'Majestic Cineplex'], ['location' => 'Colombo 04', 'total_seats' => 80]);
        $theatre3 = Theatre::updateOrCreate(['name' => 'Savoy Cinema'], ['location' => 'Wellawatte', 'total_seats' => 120]);

        // Showtimes
        foreach ($movieModels as $movie) {
            foreach ([$theatre1, $theatre2, $theatre3] as $index => $theatre) {
                // Morning
                Showtime::create([
                    'movie_id' => $movie->id,
                    'theatre_id' => $theatre->id,
                    'showtime' => now()->addDays(1)->setTime(10, 30),
                    'ticket_price' => 800.00,
                    'available_seats' => $theatre->total_seats,
                    'language' => 'English',
                    'format' => '2D'
                ]);
                // Evening
                Showtime::create([
                    'movie_id' => $movie->id,
                    'theatre_id' => $theatre->id,
                    'showtime' => now()->addDays(1)->setTime(18, 00),
                    'ticket_price' => 1200.00,
                    'available_seats' => $theatre->total_seats,
                    'language' => 'English',
                    'format' => 'IMAX'
                ]);
            }
        }
    }
}
