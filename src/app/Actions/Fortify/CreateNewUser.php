<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // RegisterRequest のルール・メッセージを使用してバリデーション
        $request = new RegisterRequest();
        $validator = validator($input, $request->rules(), $request->messages());
        $validator->validate();

        // ユーザーを作成
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        // メール認証メールを送信
        $user->sendEmailVerificationNotification();

        return $user;
    }
}
