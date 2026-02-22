@extends('layouts.app')

@section('content')
<div class="card" style="max-width: 640px;">
    <div class="card-body">
        <h1 class="h5 mb-1">コメントを編集</h1>
        <p class="text-muted small mb-4">
            投稿：<a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
        </p>

        <form method="POST" action="{{ route('comments.update', [$post, $comment]) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">本文 <span class="text-danger">*</span></label>
                <textarea name="body" rows="4"
                          class="form-control @error('body') is-invalid @enderror"
                          maxlength="1000">{{ old('body', $comment->body) }}</textarea>
                @error('body')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">更新する</button>
                <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-secondary">キャンセル</a>
            </div>
        </form>
    </div>
</div>
@endsection
