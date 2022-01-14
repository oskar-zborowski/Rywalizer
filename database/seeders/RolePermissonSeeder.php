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
            ]
        ]);
    }
}
