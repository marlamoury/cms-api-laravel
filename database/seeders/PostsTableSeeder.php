<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Tag;

class PostsTableSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $tags = Tag::pluck('id')->toArray();

        $post = Post::create([
            'title' => 'Notion',
            'content' => 'Exemplo de conteúdo para o post Notion.',
            'user_id' => $user->id,
        ]);

        $post->tags()->attach(array_slice($tags, 0, 3));
    }
}
