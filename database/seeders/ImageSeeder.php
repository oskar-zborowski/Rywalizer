<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Image::insert([
            [
                'imageable_type' => 'App\Models\Announcement',
                'imageable_id' => 1,
                'filename' => 'iHsG7nnXq56xpXvvMBRqJIoEb07U4SilVgqOYuEbWJNPj2OUokuCuthX3kMP/6YK',
                'creator_id' => 1,
                'visible_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'imageable_type' => 'App\Models\Announcement',
                'imageable_id' => 2,
                'filename' => 'olBa7WnExpWSkA/sTFJ+d4k7TRzDvy6aXRupePJ+fLI1q1isskKjrZMMwkMP/6YK',
                'creator_id' => 1,
                'visible_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'imageable_type' => 'App\Models\Announcement',
                'imageable_id' => 3,
                'filename' => 'r0siyFbks4+ihjHhA29cX/4tEXjP5Q/hcyepctZKapEOlnWTq0iD2pBto0MP/6YK',
                'creator_id' => 1,
                'visible_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'imageable_type' => 'App\Models\Announcement',
                'imageable_id' => 4,
                'filename' => 'inFX7lv9gbCrgXL3HXZ8cYMuT0v24XyFUw6VcuV6XMcEuweMq3Wlh41IwUMP/6YK',
                'creator_id' => 1,
                'visible_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'imageable_type' => 'App\Models\Announcement',
                'imageable_id' => 5,
                'filename' => 'sW4z3Tfcm7aJkQLMSHNbJoB2RBfn7CKNVTixU+hiUb4yjknppG2bqNJ98UMP/6YK',
                'creator_id' => 1,
                'visible_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'imageable_type' => 'App\Models\Announcement',
                'imageable_id' => 6,
                'filename' => 'p2gpzWvQoIq8pxbWFUVBWaQxQHfp4wKHQTSBQsljSrIdlEOog16ipK1q7UMP/6YK',
                'creator_id' => 1,
                'visible_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'imageable_type' => 'App\Models\Announcement',
                'imageable_id' => 7,
                'filename' => 'il479EqpnaDoiSvSKG5FLfIKbmfk/n6cVxqvV/5EfbBOhXPo1yyLuNNKz0MP/6YK',
                'creator_id' => 1,
                'visible_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
