<?php

namespace App\Http\Controllers;

use App\Models\Post;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        $userId = auth()->id();

        $liked = $post->likes()->where('user_id', $userId)->exists();

        if ($liked) {
            $post->likes()->where('user_id', $userId)->delete();
        } else {
            $post->likes()->create(['user_id' => $userId]);
        }

        return redirect()->route('posts.show', $post);
    }
}
