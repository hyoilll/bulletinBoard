<x-guest-layout>
    <h5 class="mb-3">パスワードをお忘れの方</h5>
    <p class="text-muted small mb-4">
        メールアドレスを入力すると、パスワード再設定用のリンクをお送りします。
    </p>

    @if (session('status'))
        <div class="alert alert-success mb-3">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">メールアドレス</label>
            <input id="email" type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('login') }}" class="small">ログインに戻る</a>
            <button type="submit" class="btn btn-primary">リセットリンクを送信</button>
        </div>
    </form>
</x-guest-layout>
