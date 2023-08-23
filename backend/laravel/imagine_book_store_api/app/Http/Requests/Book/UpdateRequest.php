<?php

namespace App\Http\Requests\Book;

use App\Traits\JsonErrors;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    use JsonErrors;
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
            'title' => ['string', 'max:100'],
            'author' => ['string', 'max:100'],
            'price' => ['numeric', 'gt:0'],
            'quantity' => ['numeric', 'gte:0'],
            'book_genre_id' => [Rule::exists('book_genres', 'id')]
        ];
    }
}
