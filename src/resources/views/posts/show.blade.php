@extends('layouts.app')

@section('content')
<div class="card mb-4">
    <div class="card-body">
        {{-- ヘッダー --}}
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <h1 class="h4 mb-1">{{ $post->title }}</h1>
                <p class="text-muted small mb-0">
                    {{ $post->user->name }} ・ {{ $post->created_at->format('Y/m/d H:i') }}
                    @if ($post->category)
                        ・ <span class="badge bg-secondary">{{ $post->category->name }}</span>
                    @endif
                </p>
            </div>
            @can('update', $post)
                <div class="d-flex gap-2 ms-3">
                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-outline-primary btn-sm">編集</a>
                    <form method="POST" action="{{ route('posts.destroy', $post) }}"
                          onsubmit="return confirm('この投稿を削除しますか？')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">削除</button>
                    </form>
                </div>
            @endcan
        </div>

        <hr>
        <p class="mb-0" style="white-space: pre-wrap;">{{ $post->body }}</p>
    </div>
</div>

{{-- いいねエリア --}}
<div class="mb-4">
    <span class="me-3">♡ {{ $post->likes->count() }} いいね</span>
    @auth
        <form method="POST" action="{{ route('likes.toggle', $post) }}" class="d-inline">
            @csrf
            @if ($post->likes->contains('user_id', auth()->id()))
                <button class="btn btn-danger btn-sm">いいね済み</button>
            @else
                <button class="btn btn-outline-danger btn-sm">いいね</button>
            @endif
        </form>
    @endauth
</div>

{{-- コメントセクション --}}
<h2 class="h5 mb-3">コメント（{{ $post->comments->count() }}件）</h2>

@forelse ($post->comments as $comment)
    <div class="card mb-2">
        <div class="card-body py-2">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="fw-semibold small">{{ $comment->user->name }}</span>
                    <span class="text-muted small ms-2">{{ $comment->created_at->format('Y/m/d H:i') }}</span>
                    <p class="mb-0 mt-1">{{ $comment->body }}</p>
                </div>
                @can('update', $comment)
                    <div class="d-flex gap-2 ms-3 text-nowrap">
                        <a href="{{ route('comments.edit', [$post, $comment]) }}"
                           class="btn btn-outline-secondary btn-sm">編集</a>
                        <form method="POST" action="{{ route('comments.destroy', [$post, $comment]) }}"
                              onsubmit="return confirm('このコメントを削除しますか？')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm">削除</button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>
    </div>
@empty
    <p class="text-muted small">コメントはまだありません。</p>
@endforelse

{{-- コメント投稿フォーム --}}
@auth
    <div class="card mt-3">
        <div class="card-body">
            <h3 class="h6 mb-3">コメントを投稿</h3>
            <form method="POST" action="{{ route('comments.store', $post) }}">
                @csrf
                <div class="mb-3">
                    <textarea name="body" class="form-control @error('body') is-invalid @enderror"
                              rows="3" placeholder="コメントを入力...">{{ old('body') }}</textarea>
                    @error('body')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button class="btn btn-primary btn-sm">コメントする</button>
            </form>
        </div>
    </div>
@else
    <p class="text-muted mt-3">
        コメントするには<a href="{{ route('login') }}">ログイン</a>が必要です。
    </p>
@endauth

<div class="mt-3">
    <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary btn-sm">← 一覧に戻る</a>
</div>
@endsection
