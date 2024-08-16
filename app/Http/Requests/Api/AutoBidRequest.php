<?php

namespace App\Http\Requests\Api;

use App\Models\Item;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AutoBidRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'item_id' => 'required|uuid|exists:items,id',
            'max_bid_amount' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    $item = Item::find($this->item_id);
                    if ($item && $value <= $item->current_price) {
                        $fail('The ' . $attribute . ' must be greater than the current bid amount of ' . $item->current_amount . '.');
                    }
                },
            ],
            'bid_alert_percentage' => 'required|numeric|min:0|max:100',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], 403));
    }
}
