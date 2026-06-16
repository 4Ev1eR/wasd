<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BlogCategoryCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'     => 'required|min:2|max:200',
            'slug'      => 'nullable|max:200',
            'parent_id' => 'nullable|integer|exists:blog_categories,id',
        ];
    }
}
