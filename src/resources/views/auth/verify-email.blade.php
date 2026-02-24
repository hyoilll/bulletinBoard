<x-guest-layout>
    <div class="text-center mb-4">
        <div class="fs-2 mb-2">✉️</div>
        <h5 class="fw-bold mb-1">メールアドレスを確認してください</h5>
        <p class="text-muted small mb-0">
            ご登録のメールアドレスに確認リンクを送信しました。<br>
            リンクをクリックして認証を完了してください。
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-4">
            確認メールを再送信しました。
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary w-100">確認メールを再送信</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-3 text-center">
        @csrf
        <button type="submit" class="btn btn-link btn-sm text-muted">ログアウト</button>
    </form>
</x-guest-layout>
