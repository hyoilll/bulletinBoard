.PHONY: up down downv restart build logs bash migrate seed fresh install

## コンテナ起動
up:
	docker compose up -d

## コンテナ停止
down:
	docker compose down

## コンテナ停止＋ボリューム削除（DBデータもリセット）
downv:
	docker compose down -v

## コンテナ再起動
restart:
	docker compose restart

## イメージ再ビルド＆起動
build:
	docker compose build --no-cache && docker compose up -d

## ログ表示（全サービス）
logs:
	docker compose logs -f

## appコンテナのシェルに入る
bash:
	docker compose exec app bash

## マイグレーション実行
migrate:
	docker compose exec app php artisan migrate

## シーダー実行
seed:
	docker compose exec app php artisan db:seed

## DBリセット＋マイグレーション＋シーダー（開発時のやり直し用）
fresh:
	docker compose exec app php artisan migrate:fresh --seed

## 初回セットアップ（.envがなければ.env.exampleからコピー）
install:
	@if [ ! -f .env ]; then cp .env.example .env; echo ".env を作成しました。値を設定してください。"; exit 1; fi
	docker compose build --no-cache
	docker compose up -d
	docker compose exec app composer create-project laravel/laravel:^12.0 .
	docker compose exec app composer require laravel/breeze --dev
	docker compose exec app php artisan breeze:install blade --no-interaction
	docker compose exec app php artisan key:generate
	docker compose exec app php artisan migrate
	docker compose exec app php artisan db:seed
