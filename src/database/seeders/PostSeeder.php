<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = DB::table('users')->pluck('id');

        $posts = [
            ['user_id' => $userIds[0], 'category_id' => 1, 'title' => 'はじめまして！', 'body' => "このサービスに登録しました。よろしくお願いします！"],
            ['user_id' => $userIds[1], 'category_id' => 1, 'title' => '近所のおすすめカフェ', 'body' => "駅前に新しいカフェがオープンしました。ラテがとても美味しいです。"],
            ['user_id' => $userIds[0], 'category_id' => 2, 'title' => 'Laravelのルーティングについて', 'body' => "Route::resource と Route::apiResource の違いを教えていただけますか？"],
            ['user_id' => $userIds[2], 'category_id' => 2, 'title' => 'Dockerのポート設定', 'body' => "docker-compose.yml で \"3307:3306\" と書いた場合、ホストからは 3307 で接続するという理解で合っていますか？"],
            ['user_id' => $userIds[1], 'category_id' => 3, 'title' => 'v1.0リリースしました', 'body' => "長らく開発してきたサービスをついにリリースしました！フィードバックお待ちしております。"],
            ['user_id' => $userIds[2], 'category_id' => 4, 'title' => '今日の作業ログ', 'body' => "マイグレーション・シーダーの実装が完了しました。次はモデルとポリシーを実装します。"],
            ['user_id' => $userIds[0], 'category_id' => null, 'title' => 'カテゴリなしの投稿サンプル', 'body' => "このように category_id が NULL の投稿は、カテゴリフィルタを使わない場合のみ一覧に表示されます。"],
        ];

        foreach ($posts as $post) {
            DB::table('posts')->insert([
                'user_id'     => $post['user_id'],
                'category_id' => $post['category_id'],
                'title'       => $post['title'],
                'body'        => $post['body'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
