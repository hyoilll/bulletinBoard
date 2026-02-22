# ルーティング設計

## ルート一覧

```
// ルートリダイレクト
GET  /  →  /posts へリダイレクト

// 認証（Laravel Breeze が自動生成）
GET  /login             → ログインフォーム
POST /login             → ログイン処理
POST /logout            → ログアウト処理
GET  /register          → 会員登録フォーム
POST /register          → 会員登録処理

// 投稿
GET    /posts                         → 一覧（カテゴリ＋タイトル検索フィルタ可） ※ゲスト可
GET    /posts/create                  → 作成フォーム               [auth]
POST   /posts                         → 作成処理                   [auth]
GET    /posts/{post}                  → 詳細                       ※ゲスト可
GET    /posts/{post}/edit             → 編集フォーム               [auth][policy:update]
PUT    /posts/{post}                  → 更新処理                   [auth][policy:update]
DELETE /posts/{post}                  → 削除処理                   [auth][policy:delete]

// コメント（posts/{post} 配下のネストルート）
POST   /posts/{post}/comments                        → 投稿       [auth]
GET    /posts/{post}/comments/{comment}/edit         → 編集フォーム [auth][policy:update]
PUT    /posts/{post}/comments/{comment}              → 更新処理   [auth][policy:update]
DELETE /posts/{post}/comments/{comment}              → 削除処理   [auth][policy:delete]

// いいね（トグル）
POST   /posts/{post}/likes            → いいね追加/取り消し       [auth]
```

---

## ミドルウェア

- `auth`: ログイン必須ルートに適用
- Route Model Binding: `{post}` → `Post` モデル、`{comment}` → `Comment` モデルの自動解決
- 存在しないIDでは自動的に 404 を返す

---

## 認可（Authorization）

### PostPolicy

| メソッド | 条件 |
|---|---|
| update | `$user->id === $post->user_id` |
| delete | `$user->id === $post->user_id` |

### CommentPolicy

| メソッド | 条件 |
|---|---|
| update | `$user->id === $comment->user_id` |
| delete | `$user->id === $comment->user_id` |

コントローラ内で `$this->authorize('update', $post)` の形で呼び出す。
403 Forbidden が自動的に返される。

---

## 投稿一覧 フィルタ＆検索のクエリ仕様

```
GET /posts                               → 全投稿（フィルタなし）
GET /posts?category_id=1                 → カテゴリID=1の投稿のみ
GET /posts?title=Laravel                 → タイトルに"Laravel"を含む投稿
GET /posts?category_id=1&title=Laravel   → カテゴリ＋タイトル両方で絞り込み
```

**フィルタ動作ルール**

- カテゴリ未選択（`category_id` パラメータなし）→ カテゴリあり・なし問わず全投稿が対象
- カテゴリ選択時 → 指定カテゴリの投稿のみ（カテゴリなし投稿は非表示）
- タイトル検索は部分一致（`LIKE '%keyword%'`）

**ページネーションと検索条件の維持**

ページネーションリンク（「次のページ」「前のページ」）は、デフォルトでは `?page=2` しかURLに付かない。
`withQueryString()` を使うことで、現在の検索条件（`category_id`, `title`）もページネーションリンクに引き継ぐ。

```
# withQueryString() なしの場合
/posts?category_id=1&title=Laravel  →  次のページ: /posts?page=2  （フィルタが消える）

# withQueryString() ありの場合
/posts?category_id=1&title=Laravel  →  次のページ: /posts?category_id=1&title=Laravel&page=2  （フィルタが維持）
```

コントローラ実装イメージ：

```php
public function index(Request $request)
{
    $query = Post::with(['user', 'category'])
                 ->withCount(['comments', 'likes'])
                 ->latest();

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    if ($request->filled('title')) {
        $query->where('title', 'LIKE', '%' . $request->title . '%');
    }

    // withQueryString() で現在のURLクエリパラメータをページネーションリンクに引き継ぐ
    $posts = $query->paginate(10)->withQueryString();
    $categories = Category::all();

    return view('posts.index', compact('posts', 'categories'));
}
```
