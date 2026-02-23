<x-guest-layout>
    <h5 class="mb-3">メールアドレスの確認</h5>
    <p class="text-muted small mb-4">
        ご登録ありがとうございます。ご登録のメールアドレスに確認リンクを送信しました。
        リンクをクリックして認証を完了してください。
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-3">
            確認メールを再送信しました。
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm">確認メールを再送信</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link btn-sm text-muted">ログアウト</button>
        </form>
    </div>
</x-guest-layout>
