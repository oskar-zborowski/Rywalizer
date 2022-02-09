<?php

namespace Database\Seeders;

use App\Models\AnnouncementPayment;
use Illuminate\Database\Seeder;

class AnnouncementPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AnnouncementPayment::insert([
            [
                'announcement_id' => 1,
                'payment_type_id' => 87,
                'creator_id' => 1,
                'editor_id' => 1,
                'is_active' => true,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'announcement_id' => 2,
                'payment_type_id' => 87,
                'creator_id' => 1,
                'editor_id' => 1,
                'is_active' => true,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'announcement_id' => 3,
                'payment_type_id' => 87,
                'creator_id' => 1,
                'editor_id' => 1,
                'is_active' => true,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'announcement_id' => 4,
                'payment_type_id' => 87,
                'creator_id' => 1,
                'editor_id' => 1,
                'is_active' => true,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'announcement_id' => 5,
                'payment_type_id' => 87,
                'creator_id' => 1,
                'editor_id' => 1,
                'is_active' => true,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'announcement_id' => 6,
                'payment_type_id' => 87,
                'creator_id' => 1,
                'editor_id' => 1,
                'is_active' => true,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'announcement_id' => 7,
                'payment_type_id' => 87,
                'creator_id' => 1,
                'editor_id' => 1,
                'is_active' => true,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ]
        ]);
    }
}
