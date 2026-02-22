<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Post $post)
    {
        $post->comments()->create(
            $request->validated() + ['user_id' => auth()->id()]
        );

        return redirect()->route('posts.show', $post)
            ->with('success', 'コメントを投稿しました。');
    }

    public function edit(Post $post, Comment $comment)
    {
        $this->authorize('update', $comment);

        return view('comments.edit', compact('post', 'comment'));
    }

    public function update(CommentRequest $request, Post $post, Comment $comment)
    {
        $this->authorize('update', $comment);

        $comment->update($request->validated());

        return redirect()->route('posts.show', $post)
            ->with('success', 'コメントを更新しました。');
    }

    public function destroy(Post $post, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return redirect()->route('posts.show', $post)
            ->with('success', 'コメントを削除しました。');
    }
}
