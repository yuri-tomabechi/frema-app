<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 購入ボタンを押下すると購入が完了する()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'address' => '東京都',
        ]);

        $item = Item::factory()->create([
            'is_sold' => false,
        ]);

        $response = $this->actingAs($user)
            ->post(route('purchase.store', $item->id), [
                'payment' => 'card',
                'post_code' => '123-4567',
                'address' => '東京都',
                'building' => 'テストビル',
            ]);

        $response->assertRedirect(route('purchase.complete'));

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function 購入済み商品は商品一覧でSOLDと表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'address' => '東京都',
        ]);

        $item = Item::factory()->create([
            'is_sold' => false,
        ]);

        // 購入処理
        $this->actingAs($user)->post(route('purchase.store', $item->id), [
            'payment' => 'card',
            'post_code' => '123-4567',
            'address' => '東京都',
            'building' => 'テストビル',
        ]);

        $response = $this->get(route('item.index'));

        $response->assertSee('SOLD');
    }

    /** @test */
    public function 購入した商品はマイページの購入一覧に表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'address' => '東京都',
        ]);

        $item = Item::factory()->create();

        $this->actingAs($user)->post(route('purchase.store', $item->id), [
            'payment' => 'card',
            'post_code' => '123-4567',
            'address' => '東京都',
            'building' => 'テストビル',
        ]);

        $response = $this->actingAs($user)
            ->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee($item->name);
    }

    /** @test */
    public function 送付先住所変更画面で登録した住所が購入画面に表示される()
    {
        $user = User::factory()->create([
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
        ]);

        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('purchase.show', $item->id));

        $response->assertStatus(200);
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区1-1-1');
        $response->assertSee('テストビル101');
    }

    /** @test */
    public function 購入時に送付先住所がpurchasesに保存される()
    {
        // ユーザーと商品を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ログインして購入処理
        $response = $this->actingAs($user)
            ->post(route('purchase.store', $item->id), [
                'post_code' => '123-4567',
                'address'   => '東京都渋谷区1-1-1',
                'building'  => '101',
                'payment'   => 'convenience', // コンビニ払いの場合
            ]);

        // リダイレクトなどが正しいか
        $response->assertRedirect(route('purchase.complete'));

        // purchasesテーブルに正しく保存されているか
        $this->assertDatabaseHas('purchases', [
            'user_id'   => $user->id,
            'item_id'   => $item->id,
            'post_code' => '123-4567',
            'address'   => '東京都渋谷区1-1-1',
            'building'  => '101',
            'payment'   => 'convenience',
            'status'    => 'paid',
        ]);
    }
}
