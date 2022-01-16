<?php

namespace Database\Seeders;

use App\Models\AccountActionType;
use Illuminate\Database\Seeder;

class AccountActionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccountActionType::insert([
            [
                'account_action_type_id' => 39,
                'period' => 2592000
            ]
        ]);
    }
}
