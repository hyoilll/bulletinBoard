# DB 設計

## ER図

```
users ──< posts >── categories（任意）
             │
             ├──< comments
             │
             └──< likes
```

---

## テーブル定義

### users（Laravel Breezeが自動生成）

| カラム | 型 | NULL | デフォルト | 説明 |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | - | 主キー（AUTO_INCREMENT） |
| name | VARCHAR(255) | NO | - | 表示名 |
| email | VARCHAR(255) | NO | - | メールアドレス |
| email_verified_at | TIMESTAMP | YES | NULL | メール認証日時 |
| password | VARCHAR(255) | NO | - | ハッシュ化パスワード |
| remember_token | VARCHAR(100) | YES | NULL | ログイン保持トークン |
| created_at | TIMESTAMP | YES | NULL | 作成日時 |
| updated_at | TIMESTAMP | YES | NULL | 更新日時 |

**インデックス**

| 種別 | カラム | 説明 |
|---|---|---|
| PRIMARY KEY | id | - |
| UNIQUE | email | 重複メールアドレス防止 |

---

### categories

| カラム | 型 | NULL | デフォルト | 説明 |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | - | 主キー（AUTO_INCREMENT） |
| name | VARCHAR(100) | NO | - | カテゴリ名 |
| created_at | TIMESTAMP | YES | NULL | 作成日時 |
| updated_at | TIMESTAMP | YES | NULL | 更新日時 |

**インデックス**

| 種別 | カラム | 説明 |
|---|---|---|
| PRIMARY KEY | id | - |

---

### posts

| カラム | 型 | NULL | デフォルト | 説明 |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | - | 主キー（AUTO_INCREMENT） |
| user_id | BIGINT UNSIGNED | NO | - | 投稿者（FK: users.id） |
| category_id | BIGINT UNSIGNED | YES | NULL | カテゴリ（FK: categories.id、任意） |
| title | VARCHAR(255) | NO | - | 投稿タイトル |
| body | TEXT | NO | - | 投稿本文 |
| created_at | TIMESTAMP | YES | NULL | 作成日時 |
| updated_at | TIMESTAMP | YES | NULL | 更新日時 |

**インデックス**

| 種別 | カラム | 説明 |
|---|---|---|
| PRIMARY KEY | id | - |
| INDEX | user_id | 投稿者での絞り込み・JOIN高速化 |
| INDEX | category_id | カテゴリフィルタリング高速化 |
| INDEX | created_at | 新着順ソート高速化 |

**外部キー制約**

| カラム | 参照先 | ON DELETE |
|---|---|---|
| user_id | users.id | CASCADE（ユーザー削除時に投稿も削除） |
| category_id | categories.id | SET NULL（カテゴリ削除時はNULLに） |

---

### comments

| カラム | 型 | NULL | デフォルト | 説明 |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | - | 主キー（AUTO_INCREMENT） |
| post_id | BIGINT UNSIGNED | NO | - | 対象投稿（FK: posts.id） |
| user_id | BIGINT UNSIGNED | NO | - | 投稿者（FK: users.id） |
| body | TEXT | NO | - | コメント本文 |
| created_at | TIMESTAMP | YES | NULL | 作成日時 |
| updated_at | TIMESTAMP | YES | NULL | 更新日時 |

**インデックス**

| 種別 | カラム | 説明 |
|---|---|---|
| PRIMARY KEY | id | - |
| INDEX | post_id | 投稿詳細ページでのコメント一覧取得高速化 |
| INDEX | user_id | ユーザー別コメント取得・JOIN高速化 |

**外部キー制約**

| カラム | 参照先 | ON DELETE |
|---|---|---|
| post_id | posts.id | CASCADE（投稿削除時にコメントも削除） |
| user_id | users.id | CASCADE（ユーザー削除時にコメントも削除） |

---

### likes

| カラム | 型 | NULL | デフォルト | 説明 |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | - | 主キー（AUTO_INCREMENT） |
| post_id | BIGINT UNSIGNED | NO | - | 対象投稿（FK: posts.id） |
| user_id | BIGINT UNSIGNED | NO | - | ユーザー（FK: users.id） |
| created_at | TIMESTAMP | YES | NULL | 作成日時 |
| updated_at | TIMESTAMP | YES | NULL | 更新日時 |

**インデックス**

| 種別 | カラム | 説明 |
|---|---|---|
| PRIMARY KEY | id | - |
| UNIQUE | (post_id, user_id) | 同一ユーザーの二重いいね防止（複合ユニーク） |
| INDEX | post_id | 投稿別いいね数集計高速化（UNIQUE制約に内包） |
| INDEX | user_id | ユーザー別いいね検索高速化 |

**外部キー制約**

| カラム | 参照先 | ON DELETE |
|---|---|---|
| post_id | posts.id | CASCADE（投稿削除時にいいねも削除） |
| user_id | users.id | CASCADE（ユーザー削除時にいいねも削除） |

---

## Eloquent リレーション

| モデル | リレーション | 相手モデル | メソッド |
|---|---|---|---|
| Post | belongsTo | User | `post->user` |
| Post | belongsTo | Category | `post->category`（nullable） |
| Post | hasMany | Comment | `post->comments` |
| Post | hasMany | Like | `post->likes` |
| User | hasMany | Post | `user->posts` |
| User | hasMany | Comment | `user->comments` |
| Category | hasMany | Post | `category->posts` |
| Comment | belongsTo | Post | `comment->post` |
| Comment | belongsTo | User | `comment->user` |
| Like | belongsTo | Post | `like->post` |
| Like | belongsTo | User | `like->user` |

---

## シーダー

### CategorySeeder（初期カテゴリ）

```
雑談 / 質問 / 報告 / その他
```

### PostSeeder（開発用サンプルデータ）

- 各カテゴリに数件の投稿（カテゴリなしの投稿も含む）
- 各投稿にコメント・いいねも適宜追加
