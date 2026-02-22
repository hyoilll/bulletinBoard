@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">投稿一覧</h1>
    @auth
        <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">新規投稿</a>
    @endauth
</div>

{{-- 検索フィルタ --}}
<form method="GET" action="{{ route('posts.index') }}" class="card card-body mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-sm-4">
            <label class="form-label small mb-1">カテゴリ</label>
            <select name="category_id" class="form-select form-select-sm">
                <option value="">すべて</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-5">
            <label class="form-label small mb-1">タイトル検索</label>
            <input type="text" name="title" class="form-control form-control-sm"
                   placeholder="タイトルを検索..." value="{{ request('title') }}">
        </div>
        <div class="col-sm-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm flex-fill">検索</button>
            <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">リセット</a>
        </div>
    </div>
</form>

{{-- 投稿一覧 --}}
@forelse ($posts as $post)
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <h5 class="card-title mb-1">
                    <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                        {{ $post->title }}
                    </a>
                </h5>
                @if ($post->category)
                    <span class="badge bg-secondary ms-2 text-nowrap">{{ $post->category->name }}</span>
                @endif
            </div>
            <p class="text-muted small mb-2">
                {{ $post->user->name }} ・ {{ $post->created_at->format('Y/m/d H:i') }}
            </p>
            <div class="d-flex gap-3 text-muted small">
                <span>♡ {{ $post->likes_count }}</span>
                <span>💬 {{ $post->comments_count }}</span>
            </div>
        </div>
    </div>
@empty
    <p class="text-muted">投稿がありません。</p>
@endforelse

{{ $posts->links() }}
@endsection
