<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class EditBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:50|unique:books,title',
            'author' => 'required|string|max:30',
            'genre' => 'required|string|max:20',
            'price' => 'required|decimal:2'
        ];
    }
}
