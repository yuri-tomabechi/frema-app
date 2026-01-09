<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'item_url' => 'required|mimes:jpeg,png|max:5120',
            'categories[]' => 'required',
            'brand_name' => 'nullable|string|max:255',
            'condition' => 'required',
            'price' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return[
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '255文字以内で入力してください',
            'item_url.required' => '商品画像をアップロードしてください',
            'item_url.mimes' => '商品画像は.jpeg もしくは .png を選択してください',
            'categories[].required' => 'カテゴリーを選択してください',
            'condition.required' => '商品の状態を選択してください',
            'price.required' => '商品の価格を入力してください',
            'price.integer' => '数値型で入力してください',
            'price.min' => '0円以上で入力してください'
        ];
    }
}
