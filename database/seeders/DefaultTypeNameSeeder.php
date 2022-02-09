<?php

namespace Database\Seeders;

use App\Models\DefaultTypeName;
use Illuminate\Database\Seeder;

class DefaultTypeNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DefaultTypeName::insert([
            [
                'name' => 'API_PERMISSION',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ROLE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'AGREEMENT_TYPE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'AUTHENTICATION_TYPE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'IMAGE_TYPE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'GENDER',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ACCOUNT_OPERATION_TYPE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'CLIENT_PERMISSION',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'PROVIDER',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ACCOUNT_ACTION_TYPE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'AREA_TYPE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'SPORT',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'PARTNER_TYPE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'VISIBLE_FIELD',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'GAME_VARIANT',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'AGE_CATEGORY',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ANNOUNCEMENT_TYPE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ANNOUNCEMENT_STATUS',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'PAYMENT_TYPE',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
