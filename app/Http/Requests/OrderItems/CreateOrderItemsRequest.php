<?php

namespace App\Http\Requests\OrderItems;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderItemsRequest extends FormRequest
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
            'added_books' => 'required|array',
            'added_books.*book_id' => 'required|integer|exists:books,id',
            'added_books.*quantity' => 'required|integer',
            'added_books.*book_price' => 'required|integer|exists:books,price'
        ];
    }
}
