<x-guest-layout>
    <h5 class="fw-bold mb-2">パスワードの確認</h5>
    <p class="text-muted small mb-4">
        セキュリティ保護されたエリアです。続行する前にパスワードを確認してください。
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-4">
            <label for="password" class="form-label">パスワード</label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary px-4">確認する</button>
        </div>
    </form>
</x-guest-layout>
