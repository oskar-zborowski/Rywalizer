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
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 2,
                'name' => 'GUEST',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'auth-register',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 2,
                'name' => 'USER',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 3,
                'name' => 'REGISTRATION_FORM',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 4,
                'name' => 'REGISTRATION_FORM',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 4,
                'name' => 'LOGIN_FORM',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 5,
                'name' => 'AVATAR',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 6,
                'name' => 'MALE',
                'description_simple' => 'Mężczyzna',
                'icon_id' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 6,
                'name' => 'FEMALE',
                'description_simple' => 'Kobieta',
                'icon_id' => 2,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 7,
                'name' => 'EMAIL_VERIFICATION',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 8,
                'name' => 'auth-test',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'auth-logout',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'user-sendVerificationEmail',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'user-verifyEmail',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'account-forgotPassword',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'account-resetPassword',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 7,
                'name' => 'PASSWORD_RESET',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'user-getUser',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'auth-redirectToProvider',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'account-restoreAccount',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'auth-handleProviderCallback',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'auth-logoutAll',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 7,
                'name' => 'ACCOUNT_RESTORATION',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 9,
                'name' => 'FACEBOOK',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 9,
                'name' => 'GOOGLE',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'defaultType-getGenders',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'defaultType-getProviders',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'user-updateUser',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 4,
                'name' => 'TOKEN_REFRESHING',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'user-uploadAvatar',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'user-changeAvatar',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'user-deleteAvatar',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 4,
                'name' => 'REGISTRATION_FACEBOOK',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 4,
                'name' => 'REGISTRATION_GOOGLE',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 4,
                'name' => 'LOGIN_FACEBOOK',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 4,
                'name' => 'LOGIN_GOOGLE',
                'description_simple' => null,
                'icon_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
