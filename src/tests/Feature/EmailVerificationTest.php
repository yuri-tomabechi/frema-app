<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use App\Models\User;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 会員登録後に認証メールが送信される()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '11111111',
            'password_confirmation' => '11111111',
        ]);

        // ユーザーが作成されている
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);

        // 認証メールが送信されている
        Notification::assertSentTo(
            $user,
            VerifyEmail::class
        );
    }

    /** @test */
    public function メール認証完了後にプロフィール設定画面へ遷移する()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        // 認証リンクを踏む（※ログイン必須）
        $this->actingAs($user)
            ->get($verificationUrl);

        // 認証済みになっている
        $this->assertNotNull($user->fresh()->email_verified_at);

        // 認証後トップ → プロフィール設定へ
        $response = $this->actingAs($user)
            ->followingRedirects()
            ->get('/');

        $response->assertStatus(200);
        $response->assertSee('プロフィール');
    }
}
