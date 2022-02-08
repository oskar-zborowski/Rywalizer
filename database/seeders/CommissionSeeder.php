<?php

namespace Database\Seeders;

use App\Models\Commission;
use Illuminate\Database\Seeder;

class CommissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Commission::insert([
            [
                'name' => 'ANNOUNCEMENT_PARTNER_COMMISSION',
                'signature' => 'ANNOUNCEMENT_PARTNER_COMMISSION',
                'version' => 1,
                'value' => 0.05,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
