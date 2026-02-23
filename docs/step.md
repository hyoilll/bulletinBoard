# 実装ステップ記録

このファイルでは各機能の実装ステップを記録します。

---

## feat/otp-registration: 会員登録OTP認証

### 概要

Issue: https://github.com/hyoilll/bulletinBoard/issues/8

登録フォーム送信後にメールでOTPコード（6桁・10分有効）を送信し、
コード入力を完了した後にユーザーをDBに作成する登録前メール認証フロー。

**変更前:** 登録フォーム → DBにユーザー作成（未認証） → /email/verify へ
**変更後:** 登録フォーム → OTPメール送信 → OTP入力画面 → ユーザーDB作成（認証済み） → ログイン

### 実装ステップ

#### Step 1: ブランチ作成
```bash
git checkout main && git pull
git checkout -b feat/otp-registration
```

#### Step 2: docs/step.md 作成（本ファイル）

#### Step 3: RegistrationOtpMail クラス作成
- `src/app/Mail/RegistrationOtpMail.php` を新規作成
- コンストラクタで OTP コードを受け取る
- メール件名: 「【掲示板】メールアドレスの確認コード」
- ビュー: `emails.registration-otp`

#### Step 4: メールテンプレート作成
- `src/resources/views/emails/registration-otp.blade.php` を新規作成
- OTPコードを大きく表示
- 「10分以内に入力してください」注記

#### Step 5: OTP入力画面 作成
- `src/resources/views/auth/registration-otp.blade.php` を新規作成
- `x-guest-layout` を使用
- 6桁コード入力フォーム（`inputmode="numeric"`, `maxlength="6"`）

#### Step 6: RegisteredUserController を変更
- `store()`: バリデーション → OTP生成 → キャッシュ保存（10分）→ メール送信 → register.otp へリダイレクト
- `showOtpForm()`: OTP入力画面を表示（セッション/キャッシュ確認あり）
- `verifyOtp()`: OTP照合 → User::create（email_verified_at=now()）→ ログイン → posts.index

#### Step 7: routes/auth.php にOTPルートを追加
```php
Route::get('/register/otp', [RegisteredUserController::class, 'showOtpForm'])->name('register.otp');
Route::post('/register/otp', [RegisteredUserController::class, 'verifyOtp'])->name('register.verify-otp');
```

#### Step 8: User モデルから MustVerifyEmail を削除
- 全ユーザーが登録時に `email_verified_at` が設定されるため不要

### 動作確認

1. `/register` でフォーム送信 → `/register/otp` へ遷移
2. Mailpit（http://localhost:8025）でOTPコード確認
3. OTP入力 → usersテーブルにレコード作成（`email_verified_at` 設定済み）
4. `/posts` にリダイレクト（「登録が完了しました！」フラッシュ表示）
5. 誤ったコード入力 → 「コードが正しくありません」エラー
6. 10分後 → 「コードが期限切れ」エラーで登録フォームへ
