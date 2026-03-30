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
                'name' => '田中花子',
                'email' => 'hanako@icloud.com',
                'password' => Hash::make('11111111'),
                'email_verified_at' => now(),
            ],
            [
                'name' => '山田太郎',
                'email' => 'taro@icloud.com',
                'password' => Hash::make('11111111'),
                'email_verified_at' => now(),
            ],
            [
                'name' => '鈴木健人',
                'email' => 'kento@icloud.com',
                'password' => Hash::make('11111111'),
                'email_verified_at' => now(),
            ],
        ]);
    }
}
