<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Facility::insert([
            [
                'name' => 'SP nr 1',
                'street' => 'Hezjoda 15',
                'address_coordinates' => '0ApNijetwdTt6niUTAwKIv51HhiU',
                'city_id' => 5,
                'creator_id' => 1,
                'editor_id' => 1,
                'visible_at' => '2022-02-09 14:29:58',
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'name' => 'SP nr 2',
                'street' => 'Warszawska 10b',
                'address_coordinates' => '0ApNijOnxtXo6HKTVBsFI/RxHxY=',
                'city_id' => 5,
                'creator_id' => 1,
                'editor_id' => 1,
                'visible_at' => '2022-02-09 14:29:58',
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'name' => 'Gimnazjum nr 45',
                'street' => 'Aleje Solidarności 49',
                'address_coordinates' => '0ApNijCowtXs4HiUTAwLJPN0HheX',
                'city_id' => 5,
                'creator_id' => 1,
                'editor_id' => 1,
                'visible_at' => '2022-02-09 14:29:58',
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ]
        ]);
    }
}
