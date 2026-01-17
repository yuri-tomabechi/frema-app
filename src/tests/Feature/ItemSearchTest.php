<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Item;
use Tests\TestCase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品名で部分一致検索ができる()
    {
        // 検索に引っかかる商品
        Item::factory()->create([
            'name' => '青い服'
        ]);

        // 検索に引っかからない商品
        Item::factory()->create([
            'name' => '赤い靴'
        ]);

        // キーワード「服」で検索
        $response = $this->get('/search?keyword=服');

        // 200OK
        $response->assertStatus(200);

        // レスポンスに「青い服」は含まれる
        $response->assertSee('青い服');

        // レスポンスに「赤い靴」は含まれない
        $response->assertDontSee('赤い靴');
    }
}
