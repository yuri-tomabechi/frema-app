<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品一覧に全商品が表示される()
    {
        $items = Item::factory()->count(3)->create();

        $response = $this->get(route('item.index'));

        $response->assertStatus(200);

        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }


    /** @test */
    public function 購入済み商品は_sold_と表示される()
    {
        $item = Item::factory()->create([
            'is_sold' => true,
        ]);

        $response = $this->get(route('item.index'));

        $response->assertSee('SOLD');
    }
}
