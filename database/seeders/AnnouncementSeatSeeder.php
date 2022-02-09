<?php

namespace Database\Seeders;

use App\Models\AnnouncementSeat;
use Illuminate\Database\Seeder;

class AnnouncementSeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AnnouncementSeat::insert([
            [
                'announcement_id' => 1,
                'sports_position_id' => 2,
                'maximum_seats_number' => 2,
                'creator_id' => 1,
                'editor_id' => 1,
                'is_active' => true,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'announcement_id' => 2,
                'sports_position_id' => 2,
                'maximum_seats_number' => 2,
                'creator_id' => 1,
                'editor_id' => 1,
                'is_active' => true,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'announcement_id' => 3,
                'sports_position_id' => 2,
                'maximum_seats_number' => 2,
                'creator_id' => 1,
                'editor_id' => 1,
                'is_active' => true,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'announcement_id' => 4,
                'sports_position_id' => 2,
                'maximum_seats_number' => 2,
                'creator_id' => 1,
                'editor_id' => 1,
                'is_active' => true,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'announcement_id' => 5,
                'sports_position_id' => 2,
                'maximum_seats_number' => 2,
                'creator_id' => 1,
                'editor_id' => 1,
                'is_active' => true,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'announcement_id' => 6,
                'sports_position_id' => 2,
                'maximum_seats_number' => 2,
                'creator_id' => 1,
                'editor_id' => 1,
                'is_active' => true,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'announcement_id' => 7,
                'sports_position_id' => 2,
                'maximum_seats_number' => 2,
                'creator_id' => 1,
                'editor_id' => 1,
                'is_active' => true,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
        ]);
    }
}
