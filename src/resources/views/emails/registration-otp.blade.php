<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 480px; margin: 40px auto; background: #fff; border-radius: 8px; padding: 40px; }
        .otp { font-size: 40px; font-weight: bold; letter-spacing: 8px; color: #0d6efd; text-align: center; margin: 32px 0; }
        .note { color: #6c757d; font-size: 14px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>メールアドレスの確認</h2>
        <p>掲示板への登録ありがとうございます。<br>以下の確認コードを入力して、登録を完了してください。</p>
        <div class="otp">{{ $otp }}</div>
        <p class="note">このコードは <strong>10分間</strong> 有効です。</p>
        <p class="note">このメールに心当たりがない場合は無視してください。</p>
    </div>
</body>
</html>
