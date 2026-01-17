<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function いいねした商品だけが表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'address' => '東京都',
        ]);

        $likedItem = Item::factory()->create();
        $notLikedItem = Item::factory()->create();

        // いいね作成
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('like.index'));

        $response->assertStatus(200);
        $response->assertSee($likedItem->name);
        $response->assertDontSee($notLikedItem->name);
    }


    /** @test */
    public function 購入済み商品は_sold_と表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'address' => '東京都',
        ]);

        $item = Item::factory()->create([
            'is_sold' => true,
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('like.index'));

        $response->assertSee('SOLD');
}

    /** @test */
    public function 未認証の場合はリダイレクトされる()
    {
        $response = $this->get(route('like.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function いいねアイコンを押下すると商品をいいねできる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('item.like', $item->id));

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function いいね済みの場合アイコンの色が変化する()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 先にいいねしておく
        $user->likedItems()->attach($item->id);

        $this->actingAs($user);

        $response = $this->get(route('item.like', $item->id));

        $response->assertSee('active');
    }

    /** @test */
    public function 再度いいねアイコンを押下するといいねを解除できる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 事前にいいね
        $user->likedItems()->attach($item->id);

        $this->actingAs($user);

        $response = $this->post(route('item.like', $item->id));

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}