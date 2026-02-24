@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4 p-md-5">
                <h1 class="h5 fw-bold mb-1">コメントを編集</h1>
                <p class="text-muted small mb-4">
                    投稿：<a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                </p>

                <form method="POST" action="{{ route('comments.update', [$post, $comment]) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label">本文 <span class="text-danger">*</span></label>
                        <textarea name="body" rows="5"
                                  class="form-control @error('body') is-invalid @enderror"
                                  maxlength="1000">{{ old('body', $comment->body) }}</textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">更新する</button>
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-secondary">キャンセル</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
