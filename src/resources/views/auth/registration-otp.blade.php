<x-guest-layout>
    <div class="text-center mb-4">
        <div class="fs-2 mb-2">📬</div>
        <h5 class="fw-bold mb-1">確認コードを入力</h5>
        <p class="text-muted small mb-0">
            登録したメールアドレスに6桁のコードを送信しました。<br>
            10分以内に入力してください。
        </p>
    </div>

    @if ($errors->has('otp') || $errors->has('email') || $errors->has('token'))
        <div class="alert alert-danger mb-3">
            {{ $errors->first('otp') ?? $errors->first('email') ?? $errors->first('token') }}
        </div>
    @endif

    <form method="POST" action="{{ route('register.verify-otp') }}">
        @csrf
        <div class="mb-4">
            <label for="otp" class="form-label">確認コード</label>
            <input
                id="otp"
                type="text"
                name="otp"
                class="form-control form-control-lg text-center fw-bold ls-4 @error('otp') is-invalid @enderror"
                inputmode="numeric"
                maxlength="6"
                pattern="[0-9]{6}"
                placeholder="000000"
                autocomplete="one-time-code"
                autofocus
                required
                style="letter-spacing: .4em; font-size: 1.5rem;"
            >
        </div>
        <button type="submit" class="btn btn-primary w-100">確認する</button>
    </form>
</x-guest-layout>
