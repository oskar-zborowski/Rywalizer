<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'first_name' => '3FUF+zXkqJeznzfdA3B0YIQPf3zD4n2VLxGzWvBa',
                'last_name' => 'sA8H1HfRxt6ZnRn8M3ICR6ExbE670i67RjG3QvpB',
                'email' => 'qgAt51PMgoeXoAnfImNfJpRzZVrC5zSmUy+sRtQferpOjGmLsmuQo5N14TUk4vA+DlymnqD7V3Ia50IgDpNs0Pt+LFhwmszJ0a80qeC0cgEwaA3C4b9WiWI2cCtsTZgXJ8xF56G7I2CkXDCrTu/r1YK2zodGIeulyRY4cxXbL28+aC9WUjL9sw7QV5EZCHNRH6EpFIy7N3LgvENrxdcMq0M6bR8A+5vRvj1IQ1LZxazMS0zXc+9MlL2YQsf1rulCmFtfWFABeGIYtP9oJPvDLXVPpEGH1A6TezWXiXyFE8icTBpEpKVkY/MBeVlBJyahDXO3ghm9ugeourlQzx4=',
                'password' => '$2y$10$jYsC1I9Hm2dmxQNJbE1Tg.XvV6HSLF7vx6Y383Q5GRkCWbyupKJuW',
                'birth_date' => '1AFahi6vw8vr4Q==',
                'gender_id' => 9,
                'role_id' => 4,
                'email_verified_at' => '2022-02-09 14:30:21',
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ]
        ]);
    }
}
