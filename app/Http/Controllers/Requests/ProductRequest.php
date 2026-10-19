<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', Rule::in(array_keys(config('khalil.product_categories')))],
            'brand' => ['nullable', 'string', 'max:80'],
            'gender' => ['nullable', Rule::in(['men', 'women', 'unisex', 'kids'])],
            'frame_shape' => ['nullable', 'string', 'max:40'],
            'description' => ['nullable', 'string', 'max:3000'],
            'price' => ['required', 'integer', 'min:0', 'max:10000000'],
            'sale_price' => ['nullable', 'integer', 'min:0', 'lt:price'],
            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'remove_image' => ['boolean'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return ['sale_price.lt' => 'The sale price must be lower than the regular price.'];
    }
}
