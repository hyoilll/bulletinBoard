<x-guest-layout>
    <h5 class="fw-bold mb-2">パスワードをお忘れの方</h5>
    <p class="text-muted small mb-4">
        メールアドレスを入力すると、パスワード再設定用のリンクをお送りします。
    </p>

    @if (session('status'))
        <div class="alert alert-success mb-3">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label">メールアドレス</label>
            <input id="email" type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">リセットリンクを送信</button>

        <p class="text-center small mt-3 mb-0">
            <a href="{{ route('login') }}">ログインに戻る</a>
        </p>
    </form>
</x-guest-layout>
