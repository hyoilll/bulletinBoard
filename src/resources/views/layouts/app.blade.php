<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Korea x Japan Talk') }}</title>
    <link rel="icon" type="image/svg+xml" href="/img/logo.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top app-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('posts.index') }}">
            <img src="/img/logo.svg" width="28" height="28" alt="Korea x Japan Talk ロゴ">
            Korea x Japan Talk
        </a>
        <div class="ms-auto d-flex align-items-center gap-2">
            @auth
                <span class="nav-user">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button class="btn btn-outline-secondary btn-sm">ログアウト</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">ログイン</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">新規登録</a>
            @endauth
        </div>
    </div>
</nav>

<main class="container py-5">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
