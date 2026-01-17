<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function プロフィールページで出品商品一覧が表示される()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'icon_url' => 'test_icon.png',
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
        ]);

        // 出品商品
        $item1 = Item::factory()->create(['user_id' => $user->id, 'name' => '出品商品1']);
        $item2 = Item::factory()->create(); // 他ユーザーの商品

        // 出品商品ページ（?page=sell）にアクセス
        $response = $this->actingAs($user)
            ->get('/mypage?page=sell');

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('test_icon.png');
        $response->assertSee('出品商品1');        // 自分の出品商品
        $response->assertDontSee($item2->name);   // 他ユーザーの商品は表示されない
    }

    /** @test */
    public function プロフィールページで購入商品一覧が表示される()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'icon_url' => 'test_icon.png',
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
        ]);

        $otherUser = User::factory()->create();

        // 他ユーザーの商品を購入
        $item = Item::factory()->create(['user_id' => $otherUser->id, 'name' => '購入商品1']);
        $user->purchases()->create([
            'item_id' => $item->id,
            'payment' => 'convenience',
            'status' => 'paid',
            'post_code' => '123-4567',
            'address'   => '東京都渋谷区1-1-1',
            'building'  => '101',
        ]);

        // 購入商品ページ（?page=buy）にアクセス
        $response = $this->actingAs($user)
            ->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('test_icon.png');
        $response->assertSee('購入商品1');        // 自分が購入した商品
    }

    /** @test */
    public function プロフィール変更画面に初期値がセットされている()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'icon_url' => 'test_icon.png',
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
        ]);

        $response = $this->actingAs($user)
            ->get(route('profile.update'));

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('test_icon.png');
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区1-1-1');
    }

}
