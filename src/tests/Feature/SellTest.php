<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;

class SellTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function 商品出品画面で必要な情報が保存できる()
    {
        // ユーザー作成
        $user = User::factory()->create();

        // カテゴリ作成
        $category = Category::factory()->create(['name' => 'テストカテゴリ']);

        // 出品フォームデータ
        $data = [
            'user_id'    => $user->id,
            'name'       => 'テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'これはテスト商品の説明です',
            'price'      => 5000,
            'condition'  => '良好',
            'categories' => [$category->id],  // pivot用
            'item_url'   => 'https://via.placeholder.com/640x480.png/0088ee?text=test',
        ];

        // 出品アクション
        $response = $this->actingAs($user)
            ->post(route('items.store'), $data);

        // 出品後、商品一覧ページにリダイレクトされる
        $response->assertRedirect(route('item.index'));

        // itemsテーブルに正しく保存されていることを確認
        $this->assertDatabaseHas('items', [
            'user_id'    => $user->id,
            'name'       => 'テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'これはテスト商品の説明です',
            'price'      => 5000,
            'condition'  => '良好',
        ]);

        // pivotテーブル category_item に正しく紐づいていることを確認
        $item = Item::first(); // 作成されたアイテム取得
        $this->assertDatabaseHas('category_item', [
            'item_id'     => $item->id,
            'category_id' => $category->id,
        ]);
    }
    
}
