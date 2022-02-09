<?php

namespace Database\Seeders;

use App\Models\SportsPosition;
use Illuminate\Database\Seeder;

class SportsPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SportsPosition::insert([
            [
                'sport_id' => 46,
                'name' => "Przyjmujący",
                'visible_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'sport_id' => 46,
                'name' => "Atakujący",
                'visible_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'sport_id' => 46,
                'name' => "Rozgrywający",
                'visible_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'sport_id' => 46,
                'name' => "Środkowy",
                'visible_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'sport_id' => 46,
                'name' => "Libero",
                'visible_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'sport_id' => 46,
                'name' => "Bez pozycji",
                'visible_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
