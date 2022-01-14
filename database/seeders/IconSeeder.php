<?php

namespace Database\Seeders;

use App\Models\Icon;
use Illuminate\Database\Seeder;

class IconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Icon::insert([
            [
                'name' => "MALE_ICON",
                'filename' => "male-icon.png",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => "FEMALE_ICON",
                'filename' => "female-icon.png",
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
