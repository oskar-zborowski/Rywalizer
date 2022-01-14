<?php

namespace Database\Seeders;

use App\Models\DefaultType;
use Illuminate\Database\Seeder;

class DefaultTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DefaultType::insert([
            [
                'default_type_name_id' => 1,
                'name' => 'auth-login',
                'description_simple' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 2,
                'name' => 'GUEST',
                'description_simple' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'auth-register',
                'description_simple' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 2,
                'name' => 'USER',
                'description_simple' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 3,
                'name' => 'REGISTRATION_FORM',
                'description_simple' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 4,
                'name' => 'REGISTRATION_FORM',
                'description_simple' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 4,
                'name' => 'LOGIN_FORM',
                'description_simple' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 5,
                'name' => 'AVATAR',
                'description_simple' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 6,
                'name' => 'MALE',
                'description_simple' => 'Mężczyzna',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 6,
                'name' => 'FEMALE',
                'description_simple' => 'Kobieta',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 7,
                'name' => 'EMAIL_VERIFICATION',
                'description_simple' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
