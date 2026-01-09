<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'user_id' => 1,
                'item_url' => 'images/Clock.jpg',
                'name' => '腕時計',
                'brand_name' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'condition' => '良好',
            ],
            [
                'user_id' => 1,
                'item_url' => 'images/hdd.jpg',
                'name' => 'HDD',
                'brand_name' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'condition' => '目立った傷や汚れなし',
            ],
            [
                'user_id' => 1,
                'item_url' => 'images/onion.jpg',
                'name' => '玉ねぎ3束',
                'brand_name' => 'ブランドなし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'condition' => 'やや傷や汚れあり',
            ],
            [
                'user_id' => 1,
                'item_url' => 'images/Leather_Shoes.jpg',
                'name' => '革靴',
                'brand_name' => 'ブランドなし',
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'condition' => '状態が悪い',
            ],
            [
                'user_id' => 1,
                'item_url' => 'images/Laptop.jpg',
                'name' => 'ノートPC',
                'brand_name' => 'ブランドなし',
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'condition' => '良好',
            ],
            [
                'user_id' => 1,
                'item_url' => 'images/Music_Mic.jpg',
                'name' => 'マイク',
                'brand_name' => 'ブランドなし',
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'condition' => '目立った傷や汚れなし',
            ],
            [
                'user_id' => 1,
                'item_url' => 'images/red_bag.jpg',
                'name' => 'ショルダーバッグ',
                'brand_name' => 'ブランドなし',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'condition' => 'やや傷や汚れあり',
            ],
            [
                'user_id' => 1,
                'item_url' => 'images/Tumbler.jpg',
                'name' => 'タンブラー',
                'brand_name' => 'ブランドなし',
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'condition' => '状態が悪い',
            ],
            [
                'user_id' => 1,
                'item_url' => 'images/Coffee.jpg',
                'name' => 'コーヒーミル',
                'brand_name' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'condition' => '良好',
            ],
            [
                'user_id' => 1,
                'item_url' => 'images/make_up.jpg',
                'name' => 'メイクセット',
                'brand_name' => 'ブランドなし',
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'condition' => '目立った傷や汚れなし',
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}

