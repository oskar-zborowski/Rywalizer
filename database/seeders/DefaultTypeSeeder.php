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
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 2,
                'name' => 'GUEST',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'auth-register',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 2,
                'name' => 'USER',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 3,
                'name' => 'REGISTRATION_FORM',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 4,
                'name' => 'REGISTRATION_FORM',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 4,
                'name' => 'LOGIN_FORM',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 5,
                'name' => 'AVATAR',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 6,
                'name' => 'MALE',
                'description_simple' => 'Mężczyzna',
                'icon_id' => 1,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 6,
                'name' => 'FEMALE',
                'description_simple' => 'Kobieta',
                'icon_id' => 2,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 7,
                'name' => 'EMAIL_VERIFICATION',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 8,
                'name' => 'auth-test',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'auth-logout',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'user-sendVerificationEmail',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'user-verifyEmail',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'account-forgotPassword',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'account-resetPassword',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 7,
                'name' => 'PASSWORD_RESET',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'user-getUser',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'auth-redirectToProvider',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'account-restoreAccount',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'auth-handleProviderCallback',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'auth-logoutAll',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 7,
                'name' => 'ACCOUNT_RESTORATION',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 9,
                'name' => 'FACEBOOK',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 9,
                'name' => 'GOOGLE',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'defaultType-getGenders',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'defaultType-getProviders',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'user-updateUser',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 4,
                'name' => 'TOKEN_REFRESHING',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'user-uploadAvatar',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'user-changeAvatar',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'user-deleteAvatar',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 4,
                'name' => 'REGISTRATION_FACEBOOK',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 4,
                'name' => 'REGISTRATION_GOOGLE',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 4,
                'name' => 'LOGIN_FACEBOOK',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 4,
                'name' => 'LOGIN_GOOGLE',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'account-deleteAccount',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 10,
                'name' => 'ACCOUNT_DELETION',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 11,
                'name' => 'COUNTRY',
                'description_simple' => 'Kraj',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 11,
                'name' => 'VOIVODESHIP',
                'description_simple' => 'Województwo',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 11,
                'name' => 'POVIAT',
                'description_simple' => 'Powiat',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 11,
                'name' => 'COMMUNE',
                'description_simple' => 'Gmina',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 11,
                'name' => 'CITY',
                'description_simple' => 'Miasto',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'defaultType-getSports',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 12,
                'name' => 'VOLLEYBALL',
                'description_simple' => 'Siatkówka',
                'icon_id' => null,
                'color' => '#ABCDEF',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 12,
                'name' => 'BEACH-VOLLEYBALL',
                'description_simple' => 'Siatkówka Plażowa',
                'icon_id' => null,
                'color' => '#ABCDEF',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 12,
                'name' => 'FOOTBALL',
                'description_simple' => 'Piłka Nożna',
                'icon_id' => null,
                'color' => '#ABCDEF',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 12,
                'name' => 'TENNIS',
                'description_simple' => 'Tenis',
                'icon_id' => null,
                'color' => '#ABCDEF',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 12,
                'name' => 'BASKETBALL',
                'description_simple' => 'Koszykówka',
                'icon_id' => null,
                'color' => '#ABCDEF',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 12,
                'name' => 'HANDBALL',
                'description_simple' => 'Piłka Ręczna',
                'icon_id' => null,
                'color' => '#ABCDEF',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 12,
                'name' => 'HOCKEY',
                'description_simple' => 'Hokej',
                'icon_id' => null,
                'color' => '#ABCDEF',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 12,
                'name' => 'TABLE-TENNIS',
                'description_simple' => 'Tenis Stołowy',
                'icon_id' => null,
                'color' => '#ABCDEF',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 12,
                'name' => 'SNOOKER',
                'description_simple' => 'Snooker',
                'icon_id' => null,
                'color' => '#ABCDEF',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 12,
                'name' => 'BILLIARDS',
                'description_simple' => 'Bilard',
                'icon_id' => null,
                'color' => '#ABCDEF',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 12,
                'name' => 'DARTS',
                'description_simple' => 'Dart',
                'icon_id' => null,
                'color' => '#ABCDEF',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 12,
                'name' => 'FUTSAL',
                'description_simple' => 'Futsal',
                'icon_id' => null,
                'color' => '#ABCDEF',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'defaultType-getAreas',
                'description_simple' => null,
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 13,
                'name' => 'ANNOUNCEMENT_PARTNER',
                'description_simple' => 'Partner ogłoszeń',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 13,
                'name' => 'FACILITY_PARTNER',
                'description_simple' => 'Partner obiektów',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 14,
                'name' => 'USER_FIELD',
                'description_simple' => '',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 14,
                'name' => 'PARTNER_FIELD',
                'description_simple' => '',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'partner-getPartner',
                'description_simple' => '',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'partner-createPartner',
                'description_simple' => '',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'partner-updatePartner',
                'description_simple' => '',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'partner-deletePartner',
                'description_simple' => '',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 5,
                'name' => 'LOGO',
                'description_simple' => '',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'partner-getPartnerById',
                'description_simple' => '',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'partner-uploadLogo',
                'description_simple' => '',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'partner-changeLogo',
                'description_simple' => '',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'partner-deleteLogo',
                'description_simple' => '',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'announcement-createAnnouncement',
                'description_simple' => '',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'announcement-updateAnnouncement',
                'description_simple' => '',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'announcement-getAnnouncementById',
                'description_simple' => '',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'announcement-uploadPhoto',
                'description_simple' => '',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'announcement-deletePhoto',
                'description_simple' => '',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 15,
                'name' => 'STANDARD',
                'description_simple' => 'Podstawowy',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 15,
                'name' => 'ADVANCED',
                'description_simple' => 'Zaawansowany',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 16,
                'name' => 'KID',
                'description_simple' => 'Dzieci',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 16,
                'name' => 'YOUTH',
                'description_simple' => 'Młodzież',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 16,
                'name' => 'ADULT',
                'description_simple' => 'Dorośli',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 16,
                'name' => 'SENIOR',
                'description_simple' => 'Seniorzy',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 17,
                'name' => 'PEOPLE',
                'description_simple' => 'Osoby',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 17,
                'name' => 'TEAM',
                'description_simple' => 'Drużyny',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 18,
                'name' => 'ACTIVE',
                'description_simple' => 'Aktywne',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 18,
                'name' => 'CANCELED',
                'description_simple' => 'Anulowane',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 19,
                'name' => 'CASH',
                'description_simple' => 'Gotówka',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 5,
                'name' => 'ANNOUNCEMENT_IMAGE',
                'description_simple' => '',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'default_type_name_id' => 1,
                'name' => 'announcement-getAnnouncements',
                'description_simple' => '',
                'icon_id' => null,
                'color' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
