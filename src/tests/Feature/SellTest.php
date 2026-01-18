<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;

class SellTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品出品画面で必要な情報が保存できる()
    {
        Storage::fake('public');

        // ユーザー
        $user = User::factory()->create();

        // カテゴリ
        $category = Category::factory()->create();

        // 出品データ
        $data = [
            'name'        => 'テスト商品',
            'brand_name'  => 'テストブランド',
            'description' => 'これはテスト商品の説明です',
            'price'       => 5000,
            'condition'   => '良好',
            'categories'  => [$category->id],
            'item_url'    => UploadedFile::fake()->create('item.jpg', 100, 'image/jpeg'),
        ];

        // 出品実行
        $response = $this->actingAs($user)
            ->post(route('items.store'), $data);

        // リダイレクト確認
        $response->assertRedirect('/mypage?page=sell');

        // itemsテーブルに保存されている
        $this->assertDatabaseHas('items', [
            'name'        => 'テスト商品',
            'brand_name'  => 'テストブランド',
            'description' => 'これはテスト商品の説明です',
            'price'       => 5000,
            'condition'   => '良好',
            'user_id'     => $user->id,
        ]);

        // 画像が保存されている
        $item = Item::first();
        Storage::disk('public')->assertExists($item->item_url);

        // カテゴリが紐づいている（pivot）
        $this->assertDatabaseHas('category_item', [
            'item_id'     => $item->id,
            'category_id' => $category->id,
        ]);
    }
}
