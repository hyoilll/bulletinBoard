# Docker 構成

## サービス一覧

| サービス名 | ベースイメージ | ホストポート | 役割 |
|---|---|---|---|
| app | php:8.2-fpm (カスタム) | なし（内部9000） | PHP-FPM (Laravel実行) |
| web | nginx:latest | 8080 | Webサーバ（リバースプロキシ） |
| db | mysql:8.0 | 3307 | MySQL データベース |

> DBの確認は **Sequel Ace** を使用。`localhost:3307` に直接接続する。

- ネットワーク: `bulletin_net`（ブリッジ）
- ボリューム: `db_data`（MySQLデータ永続化）

---

## ディレクトリ構成

```
laravel_mysql_docker_bulletin_board/
├── docker/
│   ├── nginx/
│   │   └── default.conf         # Nginx バーチャルホスト設定
│   └── php/
│       ├── Dockerfile           # PHP 8.2-FPM イメージ定義
│       └── php.ini              # PHP 開発用設定
├── src/                         # Laravel プロジェクトルート（composer で生成）
│   └── ...
├── docs/
│   └── *.md
├── Makefile                     # よく使うコマンドのショートカット
├── docker-compose.yml           # サービス定義
├── .env                         # Docker用環境変数（DB認証情報）
└── .gitignore
```

---

## Makefile

日常的な操作を短いコマンドで実行できるようにする。

```makefile
.PHONY: up down restart build logs bash migrate seed fresh install

# コンテナ起動
up:
	docker compose up -d

# コンテナ停止
down:
	docker compose down

# コンテナ再起動
restart:
	docker compose restart

# イメージ再ビルド＆起動
build:
	docker compose build --no-cache && docker compose up -d

# ログ表示（全サービス）
logs:
	docker compose logs -f

# appコンテナのシェルに入る
bash:
	docker compose exec app bash

# マイグレーション実行
migrate:
	docker compose exec app php artisan migrate

# シーダー実行
seed:
	docker compose exec app php artisan db:seed

# DBリセット＋マイグレーション＋シーダー（開発時のやり直し用）
fresh:
	docker compose exec app php artisan migrate:fresh --seed

# 初回セットアップ（Laravelインストール〜マイグレーションまで一括）
install:
	docker compose exec app composer create-project laravel/laravel:^12.0 .
	docker compose exec app composer require laravel/breeze --dev
	docker compose exec app php artisan breeze:install blade
	docker compose exec app php artisan key:generate
	docker compose exec app php artisan migrate
	docker compose exec app php artisan db:seed
```

**使い方**

```bash
make up       # 起動
make down     # 停止
make restart  # 再起動
make logs     # ログ確認
make bash     # コンテナ内シェル
make migrate  # マイグレーション
make fresh    # DBリセット＆再作成
make install  # 初回セットアップ
```

---

## docker-compose.yml 構成

```yaml
services:
  app:
    build: ./docker/php
    volumes:
      - ./src:/var/www/html
    networks:
      - bulletin_net

  web:
    image: nginx:latest
    ports:
      - "8080:80"
    volumes:
      - ./src:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
    networks:
      - bulletin_net

  db:
    image: mysql:8.0
    ports:
      - "3307:3306"
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
      MYSQL_DATABASE: ${DB_DATABASE}
      MYSQL_USER: ${DB_USERNAME}
      MYSQL_PASSWORD: ${DB_PASSWORD}
    volumes:
      - db_data:/var/lib/mysql
    networks:
      - bulletin_net

networks:
  bulletin_net:
    driver: bridge

volumes:
  db_data:
```

---

## .env（Docker用）

```env
DB_ROOT_PASSWORD=secret
DB_DATABASE=bulletin_board
DB_USERNAME=laravel
DB_PASSWORD=laravel
```

> このファイルは `docker-compose.yml` の `${変数名}` で参照される。`.gitignore` に追加すること。

---

## docker/php/Dockerfile

```dockerfile
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath \
    && docker-php-ext-enable pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
```

**インストールする PHP 拡張**

| 拡張 | 用途 |
|---|---|
| pdo_mysql | MySQL接続（必須） |
| mbstring | 日本語処理 |
| exif | 画像メタデータ（Laravel推奨） |
| pcntl | プロセス制御（Laravel推奨） |
| bcmath | 大きな数値演算（Laravel推奨） |

---

## docker/php/php.ini

```ini
display_errors = On
error_reporting = E_ALL
memory_limit = 256M
upload_max_filesize = 20M
post_max_size = 20M
```

---

## docker/nginx/default.conf

```nginx
server {
    listen 80;
    root /var/www/html/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

> `try_files $uri $uri/ /index.php?$query_string;` は Laravel のルーティングに必須。これがないと `/posts` 等のURLが全て 404 になる。

---

## src/.env（Laravel用）

Laravel の `.env` に以下を設定（`DB_HOST` はコンテナのサービス名 `db` を指定）：

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=bulletin_board
DB_USERNAME=laravel
DB_PASSWORD=laravel
```

---

## Sequel Ace での接続設定

| 項目 | 値 |
|---|---|
| ホスト | 127.0.0.1 |
| ユーザー名 | laravel |
| パスワード | laravel |
| データベース | bulletin_board |
| ポート | 3307 |

---

## トラブルシューティング

### storage への書き込みエラーが出る場合

Laravel が `storage/` や `bootstrap/cache/` に書き込めないエラーが発生した場合：

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
```

> macOS + Docker Desktop 環境では通常発生しないが、発生した場合の対処コマンド。
