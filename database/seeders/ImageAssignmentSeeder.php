<?php

namespace Database\Seeders;

use App\Models\ImageAssignment;
use Illuminate\Database\Seeder;

class ImageAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ImageAssignment::insert([
            [
                'imageable_type' => 'App\Models\Announcement',
                'imageable_id' => 1,
                'image_type_id' => 88,
                'image_id' => 1,
                'number' => 1,
                'creator_id' => 1,
                'editor_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'imageable_type' => 'App\Models\Announcement',
                'imageable_id' => 2,
                'image_type_id' => 88,
                'image_id' => 2,
                'number' => 1,
                'creator_id' => 1,
                'editor_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'imageable_type' => 'App\Models\Announcement',
                'imageable_id' => 3,
                'image_type_id' => 88,
                'image_id' => 3,
                'number' => 1,
                'creator_id' => 1,
                'editor_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'imageable_type' => 'App\Models\Announcement',
                'imageable_id' => 4,
                'image_type_id' => 88,
                'image_id' => 4,
                'number' => 1,
                'creator_id' => 1,
                'editor_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'imageable_type' => 'App\Models\Announcement',
                'imageable_id' => 5,
                'image_type_id' => 88,
                'image_id' => 5,
                'number' => 1,
                'creator_id' => 1,
                'editor_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'imageable_type' => 'App\Models\Announcement',
                'imageable_id' => 6,
                'image_type_id' => 88,
                'image_id' => 6,
                'number' => 1,
                'creator_id' => 1,
                'editor_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'imageable_type' => 'App\Models\Announcement',
                'imageable_id' => 7,
                'image_type_id' => 88,
                'image_id' => 7,
                'number' => 1,
                'creator_id' => 1,
                'editor_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
