<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Like;
use App\Models\Comment;

class DetailItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品詳細ページに必要な情報が表示される()
    {
        $seller = User::factory()->create();
        $commentUser = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 5000,
            'description' => 'これはテスト商品です',
            'condition' => '良好',
        ]);

        $categories = Category::factory()->count(2)->create();
        $item->categories()->attach($categories->pluck('id'));

        Like::factory()->create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
        ]);

        Comment::factory()->create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
            'comment' => 'とても良い商品ですね',
        ]);

        $response = $this->get(route('item.detail', $item->id));

        $response->assertStatus(200);

        // 商品情報
        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('¥5,000');
        $response->assertSee('これはテスト商品です');
        $response->assertSee('良好');

        // いいね数・コメント数
        $response->assertSee('1'); // いいね数
        $response->assertSee('1'); // コメント数

        // コメント情報
        $response->assertSee('とても良い商品ですね');
        $response->assertSee($commentUser->name);
    }

    /** @test */
    public function 複数選択されたカテゴリが表示されている()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $user->id,
        ]);

        $categories = Category::factory()->count(2)->create();

        $item->categories()->attach($categories->pluck('id'));

        $response = $this->get(route('item.detail', $item->id));

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }
}
