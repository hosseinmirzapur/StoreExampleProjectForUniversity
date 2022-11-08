<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->isMethod('post') ? $this->postRules() : $this->putRules();
    }

    #[ArrayShape([
        'category_id' => "array",
        'name' => "string",
        'image' => "string[]",
        'price' => "string",
        'description' => "string",
        'ingredients' => "string",
        'origins' => "string"
    ])] protected function postRules(): array
    {
        return [
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'name' => 'required',
            'image' => ['required', 'mimes:jpeg,png,jpg,gif'],
            'price' => 'required',
            'description' => 'nullable',
            'ingredients' => 'nullable',
            'origins' => 'nullable'
        ];
    }

    #[ArrayShape([
        'category_id' => "array",
        'name' => "string",
        'image' => "string[]",
        'price' => "string",
        'description' => "string",
        'ingredients' => "string",
        'origins' => "string"
    ])] protected function putRules(): array
    {
        return [
            'category_id' => ['nullable', Rule::exists('categories', 'id')],
            'name' => 'nullable',
            'image' => ['nullable', 'mimes:jpeg,png,jpg,gif'],
            'price' => 'nullable',
            'description' => 'nullable',
            'ingredients' => 'nullable',
            'origins' => 'nullable'
        ];
    }
}
