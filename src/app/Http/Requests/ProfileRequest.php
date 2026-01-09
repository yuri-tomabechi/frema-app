<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'  => 'required|string|max:20',
            'post_code' => 'required|string|regex:/^\d{3}-\d{4}$/',
            'address'   => 'required|string|max:255',
            'building'  => 'nullable|string|max:255',
            'icon_url' => 'nullable|mimes:jpeg,png|max:5012',
        ];
    }

    public function messages()
    {
        return[
            'name.required' => 'ユーザー名を入力してください',
            'name.max'      => 'ユーザー名は20文字以内で入力してください',
            'post_code.required' => '郵便番号を入力してください',
            'post_code.regex' => 'ハイフンありの８文字で入力してください',
            'address.required' => '住所を入力してください',
            'icon_url.mimes'   => 'プロフィール画像は.jpeg もしくは .png を選択してください',
            'icon_url.max'     => '画像サイズは5MB以内で選択してください',
        ];
    }
}
