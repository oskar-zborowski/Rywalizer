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
            ]
        ]);
    }
}
