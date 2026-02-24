@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 fw-bold mb-0">投稿一覧</h1>
    @auth
        <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm px-3">+ 新規投稿</a>
    @endauth
</div>

{{-- 検索フィルタ --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('posts.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-sm-4">
                    <label class="form-label">カテゴリ</label>
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
                    <label class="form-label">タイトル検索</label>
                    <input type="text" name="title" class="form-control form-control-sm"
                           placeholder="タイトルを検索..." value="{{ request('title') }}">
                </div>
                <div class="col-sm-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill">検索</button>
                    <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">リセット</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- 投稿一覧 --}}
@forelse ($posts as $post)
    <div class="card card-hoverable mb-3">
        <div class="card-body px-4 py-3">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-1 fw-semibold">
                        <a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-dark stretched-link">
                            {{ $post->title }}
                        </a>
                    </h5>
                    <p class="text-muted small mb-2">
                        {{ $post->user->name }} &middot; {{ $post->created_at->format('Y/m/d H:i') }}
                    </p>
                </div>
                @if ($post->category)
                    <span class="badge bg-secondary text-nowrap">{{ $post->category->name }}</span>
                @endif
            </div>
            <div class="d-flex gap-3 small" style="color: #9ca3af;">
                <span>♡ {{ $post->likes_count }}</span>
                <span>💬 {{ $post->comments_count }}</span>
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-5 text-muted">
        <p class="mb-0">まだ投稿がありません。</p>
    </div>
@endforelse

<div class="mt-3">
    {{ $posts->links() }}
</div>
@endsection
