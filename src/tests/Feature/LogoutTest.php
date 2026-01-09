<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログアウト処理が実行される()
    {
        // ① ユーザーを作成してログイン状態にする
        $user = User::factory()->create();
        $this->actingAs($user);

        // ② ログアウト実行
        $response = $this->get('/mylogout');

        // ③ ログアウトされていることを確認
        $this->assertGuest();

        // ④ ログアウト後のリダイレクト先を確認
        $response->assertRedirect('/login');
    }
}
