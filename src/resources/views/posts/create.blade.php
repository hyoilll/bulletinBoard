@extends('layouts.app')

@section('content')
<div class="card" style="max-width: 100%;">
    <div class="card-body">
        <h1 class="h5 mb-4">新規投稿</h1>

        <form method="POST" action="{{ route('posts.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">タイトル <span class="text-danger">*</span></label>
                <input type="text" name="title"
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title') }}" maxlength="255">
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
                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">本文 <span class="text-danger">*</span></label>
                <textarea name="body" rows="8"
                          class="form-control @error('body') is-invalid @enderror">{{ old('body') }}</textarea>
                @error('body')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">投稿する</button>
                <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">キャンセル</a>
            </div>
        </form>
    </div>
</div>
@endsection
