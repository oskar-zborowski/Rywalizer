<?php

namespace Database\Seeders;

use App\Models\ImageAssignment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void {

        $this->call([
            IconSeeder::class,
            DefaultTypeNameSeeder::class,
            DefaultTypeSeeder::class,
            RolePermissonSeeder::class,
            AgreementSeeder::class,
            AccountActionTypeSeeder::class,
            CommissionSeeder::class,
            SportsPositionSeeder::class,
            UserSeeder::class,
            UserSettingSeeder::class,
            UserAgreementSeeder::class,
            PartnerSeeder::class,
            PartnerSettingSeeder::class,
            AreaSeeder::class,
            FacilitySeeder::class,
            AnnouncementSeeder::class,
            AnnouncementPaymentSeeder::class,
            AnnouncementSeatSeeder::class,
            ImageSeeder::class,
            ImageAssignmentSeeder::class,
            MinimumSkillLevelSeeder::class
        ]);

        // \App\Models\User::factory(10)->create();
    }
}
