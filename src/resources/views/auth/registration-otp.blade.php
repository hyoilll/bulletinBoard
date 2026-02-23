<x-guest-layout>
    <h5 class="mb-3">メールアドレスの確認</h5>
    <p class="text-muted small mb-4">
        登録したメールアドレスに6桁の確認コードを送信しました。<br>
        コードを入力して登録を完了してください。（10分以内に入力してください）
    </p>

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
                class="form-control form-control-lg text-center @error('otp') is-invalid @enderror"
                inputmode="numeric"
                maxlength="6"
                pattern="[0-9]{6}"
                placeholder="000000"
                autocomplete="one-time-code"
                autofocus
                required
            >
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">確認する</button>
        </div>
    </form>
</x-guest-layout>
