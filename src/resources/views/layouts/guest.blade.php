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
<body class="guest-bg">
    <div class="min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="w-100" style="max-width: 420px;">
            <div class="text-center mb-4">
                <img src="/img/logo.svg" width="52" height="52" alt="KJ" class="mb-2 d-block mx-auto">
                <a href="{{ route('posts.index') }}" class="guest-brand">Korea x Japan Talk</a>
            </div>
            <div class="card guest-card">
                <div class="card-body p-4 p-md-5">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
