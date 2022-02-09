<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Area::insert([
            [
                'name' => 'Polska',
                'area_type_id' => 40,
                'parent_id' => null,
                'creator_id' => 1,
                'editor_id' => 1,
                'visible_at' => '2022-02-09 14:29:58',
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'name' => 'Wielkopolskie',
                'area_type_id' => 41,
                'parent_id' => 1,
                'creator_id' => 1,
                'editor_id' => 1,
                'visible_at' => '2022-02-09 14:29:58',
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'name' => 'Poznański',
                'area_type_id' => 42,
                'parent_id' => 2,
                'creator_id' => 1,
                'editor_id' => 1,
                'visible_at' => '2022-02-09 14:29:58',
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'name' => 'Poznań',
                'area_type_id' => 43,
                'parent_id' => 3,
                'creator_id' => 1,
                'editor_id' => 1,
                'visible_at' => '2022-02-09 14:29:58',
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ],
            [
                'name' => 'Poznań',
                'area_type_id' => 44,
                'parent_id' => 4,
                'creator_id' => 1,
                'editor_id' => 1,
                'visible_at' => '2022-02-09 14:29:58',
                'created_at' => '2022-02-09 14:29:58',
                'updated_at' => '2022-02-09 14:29:58'
            ]
        ]);
    }
}
