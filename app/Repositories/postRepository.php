<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\Tag;

class PostRepository
{
    public function getAll($request)
    {
        $query = Post::with(['user', 'tags']);

        if ($request->has('tag')) {
            $query->whereHas('tags', fn($q) =>
                $q->where('name', $request->tag)
            );
        }

        if ($request->has('query')) {
            $query->where(fn($q) =>
                $q->where('title', 'like', "%{$request->query}%")
                  ->orWhere('content', 'like', "%{$request->query}%")
            );
        }

        return $query->paginate(10);
    }

    public function find($id)
    {
        return Post::with(['user', 'tags'])->findOrFail($id);
    }

    public function create(array $data)
    {
        $post = Post::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'user_id' => $data['user_id']
        ]);

        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        return $post->load('user', 'tags');
    }

    public function update($id, array $data)
    {
        $post = Post::findOrFail($id);
        $post->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'user_id' => $data['user_id']
        ]);

        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        return $post->load('user', 'tags');
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);
        $post->tags()->detach();
        return $post->delete();
    }
}
