<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagsTableSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'api', 'json', 'laravel', 'node', 'rest', 'webapps',
            'writing', 'calendar', 'planning', 'organization'
        ];

        foreach ($tags as $tag) {
            Tag::create(['name' => $tag]);
        }
    }
}
