<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'テスト',
                'email' => 'test@example.com',
                'password' => Hash::make('11111111'),
                'email_verified_at' => now(),
            ],
            [
                'name' => '山田太郎',
                'email' => 'taro.0123@icloud.com',
                'password' => Hash::make('12341234'),
                'email_verified_at' => now(),
            ],
        ]);
    }
}
