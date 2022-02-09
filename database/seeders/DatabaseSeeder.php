<?php

namespace Database\Seeders;

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
            SportsPositionSeeder::class
        ]);

        // \App\Models\User::factory(10)->create();
    }
}
