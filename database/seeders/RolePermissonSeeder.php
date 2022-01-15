<?php

namespace Database\Seeders;

use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class RolePermissonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RolePermission::insert([
            [
                'role_id' => 2,
                'permission_id' => 1,
                'created_at' => now()
            ],
            [
                'role_id' => 2,
                'permission_id' => 3,
                'created_at' => now()
            ],
            [
                'role_id' => 4,
                'permission_id' => 12,
                'created_at' => now()
            ],
            [
                'role_id' => 4,
                'permission_id' => 13,
                'created_at' => now()
            ],
            [
                'role_id' => 4,
                'permission_id' => 14,
                'created_at' => now()
            ],
            [
                'role_id' => 4,
                'permission_id' => 15,
                'created_at' => now()
            ],
            [
                'role_id' => 2,
                'permission_id' => 16,
                'created_at' => now()
            ],
            [
                'role_id' => 2,
                'permission_id' => 17,
                'created_at' => now()
            ],
            [
                'role_id' => 4,
                'permission_id' => 19,
                'created_at' => now()
            ],
            [
                'role_id' => 2,
                'permission_id' => 20,
                'created_at' => now()
            ],
            [
                'role_id' => 2,
                'permission_id' => 21,
                'created_at' => now()
            ],
            [
                'role_id' => 2,
                'permission_id' => 22,
                'created_at' => now()
            ],
            [
                'role_id' => 4,
                'permission_id' => 23,
                'created_at' => now()
            ],
            [
                'role_id' => 2,
                'permission_id' => 27,
                'created_at' => now()
            ],
            [
                'role_id' => 2,
                'permission_id' => 28,
                'created_at' => now()
            ],
            [
                'role_id' => 4,
                'permission_id' => 27,
                'created_at' => now()
            ],
            [
                'role_id' => 4,
                'permission_id' => 28,
                'created_at' => now()
            ],
            [
                'role_id' => 4,
                'permission_id' => 29,
                'created_at' => now()
            ],
            [
                'role_id' => 4,
                'permission_id' => 31,
                'created_at' => now()
            ],
            [
                'role_id' => 4,
                'permission_id' => 32,
                'created_at' => now()
            ],
            [
                'role_id' => 4,
                'permission_id' => 33,
                'created_at' => now()
            ]
        ]);
    }
}
