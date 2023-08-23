<?php

namespace App\Http\Requests\Cart;

use App\Rules\SufficientBookQuantity;
use App\Traits\JsonErrors;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddRequest extends FormRequest
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
            'book_id' => ['required', Rule::exists('books', 'id')],
            'quantity' => ['required', 'numeric', 'min:1', new SufficientBookQuantity($this->request->get('book_id'))]
        ];
    }
}
