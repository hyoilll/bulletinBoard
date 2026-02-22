<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // パスワードは全員 "password"（開発用）
        User::factory()->create([
            'name'  => 'テストユーザーA',
            'email' => 'user-a@example.com',
        ]);

        User::factory()->create([
            'name'  => 'テストユーザーB',
            'email' => 'user-b@example.com',
        ]);

        User::factory()->create([
            'name'  => 'テストユーザーC',
            'email' => 'user-c@example.com',
        ]);

        $this->call([
            CategorySeeder::class,
            PostSeeder::class,
        ]);
    }
}
