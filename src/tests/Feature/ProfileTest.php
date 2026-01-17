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
    public function プロフィールページで必要な情報が表示される()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'icon_url' => 'test_icon.png',
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
        ]);

        // 出品商品と購入商品を作成
        $item1 = Item::factory()->create(['user_id' => $user->id, 'name' => '出品商品1']);
        $item2 = Item::factory()->create(); // 別ユーザーの商品
        $purchase = $user->purchases()->create([
            'item_id' => $item2->id,
            'payment' => 'convenience',
            'status' => 'paid',
            'post_code' => '123-4567',          
            'address'   => '東京都渋谷区1-1-1',  
            'building'  => '101',
        ]);

        $response = $this->actingAs($user)
            ->get(url('/mypage'));

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('test_icon.png');
        $response->assertSee('出品商品1');
        $response->assertSee($item2->name); // 購入商品も表示
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
