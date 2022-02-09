<?php

namespace Database\Seeders;

use App\Models\PartnerSetting;
use Illuminate\Database\Seeder;

class PartnerSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PartnerSetting::insert([
            [
                'partner_id' => 1,
                'partner_type_id' => 59,
                'commission_id' => 1,
                'visible_name_id' => 61,
                'visible_image_id' => 61,
                'visible_email_id' => 61,
                'visible_telephone_id' => 61,
                'visible_facebook_id' => 61,
                'visible_instagram_id' => 61,
                'visible_website_id' => 61,
                'creator_id' => 1,
                'editor_id' => 1,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ]
        ]);
    }
}
