<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Partner::insert([
            [
                'user_id' => 1,
                'first_name' => '3FUF+zXkqJeznzfdA3B0YIQPf3zD4n2VLxGzWvBa',
                'last_name' => 'sA8H1HfRxt6ZnRn8M3ICR6ExbE670i67RjG3QvpB',
                'alias' => 'oskar.zborowski.1',
                'creator_id' => 1,
                'editor_id' => 1,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ]
        ]);
    }
}
