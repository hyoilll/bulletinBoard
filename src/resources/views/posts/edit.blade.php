@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4 p-md-5">
                <h1 class="h5 fw-bold mb-4">投稿を編集</h1>

                <form method="POST" action="{{ route('posts.update', $post) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">タイトル <span class="text-danger">*</span></label>
                        <input type="text" name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $post->title) }}" maxlength="255">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">カテゴリ</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">選択なし</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">本文 <span class="text-danger">*</span></label>
                        <textarea name="body" rows="10"
                                  class="form-control @error('body') is-invalid @enderror">{{ old('body', $post->body) }}</textarea>
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
