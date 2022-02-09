<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Announcement::insert([
            [
                'announcement_partner_id' => 1,
                'facility_id' => 1,
                'sport_id' => 46,
                'start_date' => '2023-02-09 14:29:58',
                'end_date' => '2023-02-09 14:30:58',
                'visible_at' => '2022-02-09 14:29:58',
                'ticket_price' => 1000,
                'game_variant_id' => 77,
                'code' => 'pAowiUfbu66P',
                'maximum_participants_number' => 2,
                'announcement_status_id' => 85,
                'is_automatically_approved' => true,
                'is_public' => true,
                'creator_id' => 1,
                'editor_id' => 1,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'announcement_partner_id' => 1,
                'facility_id' => 2,
                'sport_id' => 46,
                'start_date' => '2023-02-09 14:29:58',
                'end_date' => '2023-02-09 14:30:58',
                'visible_at' => '2022-02-09 14:29:58',
                'ticket_price' => 1250,
                'game_variant_id' => 77,
                'code' => 'pAowiUfbu66a',
                'maximum_participants_number' => 5,
                'announcement_status_id' => 85,
                'is_automatically_approved' => true,
                'is_public' => true,
                'creator_id' => 1,
                'editor_id' => 1,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'announcement_partner_id' => 1,
                'facility_id' => 3,
                'sport_id' => 46,
                'start_date' => '2023-02-09 14:29:58',
                'end_date' => '2023-02-09 14:30:58',
                'visible_at' => '2022-02-09 14:29:58',
                'ticket_price' => 1250,
                'game_variant_id' => 77,
                'code' => 'psAowUfbu66a',
                'maximum_participants_number' => 5,
                'announcement_status_id' => 85,
                'is_automatically_approved' => true,
                'is_public' => true,
                'creator_id' => 1,
                'editor_id' => 1,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'announcement_partner_id' => 1,
                'facility_id' => 1,
                'sport_id' => 46,
                'start_date' => '2023-02-09 14:29:58',
                'end_date' => '2023-02-09 14:30:58',
                'visible_at' => '2022-02-09 14:29:58',
                'ticket_price' => 1250,
                'game_variant_id' => 77,
                'code' => 'psAowUftu66a',
                'maximum_participants_number' => 5,
                'announcement_status_id' => 85,
                'is_automatically_approved' => true,
                'is_public' => true,
                'creator_id' => 1,
                'editor_id' => 1,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'announcement_partner_id' => 1,
                'facility_id' => 1,
                'sport_id' => 46,
                'start_date' => '2023-02-09 14:29:58',
                'end_date' => '2023-02-09 14:30:58',
                'visible_at' => '2022-02-09 14:29:58',
                'ticket_price' => 1250,
                'game_variant_id' => 77,
                'code' => 'psAowUftun6a',
                'maximum_participants_number' => 5,
                'announcement_status_id' => 85,
                'is_automatically_approved' => true,
                'is_public' => true,
                'creator_id' => 1,
                'editor_id' => 1,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'announcement_partner_id' => 1,
                'facility_id' => 1,
                'sport_id' => 46,
                'start_date' => '2023-02-09 14:29:58',
                'end_date' => '2023-02-09 14:30:58',
                'visible_at' => '2022-02-09 14:29:58',
                'ticket_price' => 1300,
                'game_variant_id' => 77,
                'code' => 'psAowwftu66a',
                'maximum_participants_number' => 5,
                'announcement_status_id' => 85,
                'is_automatically_approved' => true,
                'is_public' => true,
                'creator_id' => 1,
                'editor_id' => 1,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'announcement_partner_id' => 1,
                'facility_id' => 2,
                'sport_id' => 46,
                'start_date' => '2023-02-09 14:29:58',
                'end_date' => '2023-02-09 14:30:58',
                'visible_at' => '2022-02-09 14:29:58',
                'ticket_price' => 1300,
                'game_variant_id' => 77,
                'code' => 'psAowwftu66b',
                'maximum_participants_number' => 12,
                'announcement_status_id' => 85,
                'is_automatically_approved' => true,
                'is_public' => true,
                'creator_id' => 1,
                'editor_id' => 1,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
        ]);
    }
}
