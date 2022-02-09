<?php

namespace Database\Seeders;

use App\Models\UserAgreement;
use Illuminate\Database\Seeder;

class UserAgreementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserAgreement::insert([
            [
                'user_id' => 1,
                'agreement_id' => 1,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'user_id' => 1,
                'agreement_id' => 2,
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ]
        ]);
    }
}
