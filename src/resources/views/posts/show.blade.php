@extends('layouts.app')

@section('content')
<div class="mb-3">
    <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary btn-sm">← 一覧に戻る</a>
</div>

{{-- 投稿カード --}}
<div class="card mb-4">
    <div class="card-body p-4 p-md-5">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h1 class="h3 fw-bold mb-2">{{ $post->title }}</h1>
                <div class="d-flex align-items-center gap-2 text-muted small">
                    <span>{{ $post->user->name }}</span>
                    <span>&middot;</span>
                    <span>{{ $post->created_at->format('Y/m/d H:i') }}</span>
                    @if ($post->category)
                        <span class="badge bg-secondary">{{ $post->category->name }}</span>
                    @endif
                </div>
            </div>
            @can('update', $post)
                <div class="d-flex gap-2 ms-3 flex-shrink-0">
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
        <p class="mb-0 lh-lg" style="white-space: pre-wrap;">{{ $post->body }}</p>
    </div>
</div>

{{-- いいねエリア --}}
<div class="d-flex align-items-center gap-3 mb-5">
    <span class="text-muted small">♡ {{ $post->likes->count() }} いいね</span>
    @auth
        <form method="POST" action="{{ route('likes.toggle', $post) }}" class="d-inline">
            @csrf
            @if ($post->likes->contains('user_id', auth()->id()))
                <button class="btn btn-danger btn-sm px-3">♥ いいね済み</button>
            @else
                <button class="btn btn-outline-danger btn-sm px-3">♡ いいね</button>
            @endif
        </form>
    @endauth
</div>

{{-- コメントセクション --}}
<h2 class="h5 fw-bold mb-3">コメント <span class="text-muted fw-normal fs-6">（{{ $post->comments->count() }}件）</span></h2>

@forelse ($post->comments as $comment)
    <div class="card mb-2">
        <div class="card-body px-4 py-3">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="fw-semibold small">{{ $comment->user->name }}</span>
                        <span class="text-muted small">{{ $comment->created_at->format('Y/m/d H:i') }}</span>
                    </div>
                    <p class="mb-0">{{ $comment->body }}</p>
                </div>
                @can('update', $comment)
                    <div class="d-flex gap-2 ms-3 flex-shrink-0">
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
    <p class="text-muted small mb-4">コメントはまだありません。</p>
@endforelse

{{-- コメント投稿フォーム --}}
@auth
    <div class="card mt-4">
        <div class="card-body p-4">
            <h3 class="h6 fw-bold mb-3">コメントを投稿</h3>
            <form method="POST" action="{{ route('comments.store', $post) }}">
                @csrf
                <div class="mb-3">
                    <textarea name="body" class="form-control @error('body') is-invalid @enderror"
                              rows="3" placeholder="コメントを入力...">{{ old('body') }}</textarea>
                    @error('body')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button class="btn btn-primary btn-sm px-3">コメントする</button>
            </form>
        </div>
    </div>
@else
    <p class="text-muted mt-4 small">
        コメントするには<a href="{{ route('login') }}">ログイン</a>が必要です。
    </p>
@endauth
@endsection
