<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class PostRequest extends FormRequest
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
        'image' => "string[]",
        'title' => "string",
        'text' => "string",
        'tags' => "string"
    ])] protected function postRules(): array
    {
        return [
            'image' => ['required', 'mimes:jpeg,png,jpg,gif'],
            'title' => 'required',
            'text' => 'required',
            'tags' => 'nullable'
        ];
    }

    #[ArrayShape([
        'image' => "string[]",
        'title' => "string",
        'text' => "string",
        'tags' => "string"
    ])] protected function putRules(): array
    {
        return [
            'image' => ['nullable', 'mimes:jpeg,png,jpg,gif'],
            'title' => 'nullable',
            'text' => 'nullable',
            'tags' => 'nullable'
        ];
    }
}
