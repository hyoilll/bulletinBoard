<x-guest-layout>
    @if (session('status'))
        <div class="alert alert-success mb-3">{{ session('status') }}</div>
    @endif

    <h5 class="fw-bold mb-4">ログイン</h5>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">メールアドレス</label>
            <input id="email" type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">パスワード</label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4 form-check">
            <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
            <label for="remember_me" class="form-check-label small">ログイン状態を保持する</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">ログイン</button>

        <div class="d-flex justify-content-between align-items-center mt-3">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="small">パスワードをお忘れですか？</a>
            @endif
            <a href="{{ route('register') }}" class="small">新規登録はこちら</a>
        </div>
    </form>
</x-guest-layout>
