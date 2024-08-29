<?php

namespace App\Http\Requests\Api;

use App\Models\Item;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BidRequest extends FormRequest
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
            'item_id' => [
                'required',
                'uuid',
                'exists:items,id',
                function ($attribute, $value, $fail) {
                    $item = Item::find($value);
                    if ($item) {
                        if ($item->start_time > now()) {
                            $fail('The bidding period for this item has not started yet.');
                        }
                        if ($item->end_time < now()) {
                            $fail('The bidding period for this item has ended.');
                        }
                    }
                },
            ],
            'bid_amount' => 'required|numeric|min:0',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $item = Item::find($this->input('item_id'));

            if ($item) {
                $referenceAmount = $item->current_price ?? $item->starting_price;

                if ($this->input('bid_amount') <= $referenceAmount) {
                    $validator->errors()->add('bid_amount', 'The bid amount must be greater than the current amount or starting price of the item.');
                }
            }
        });
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], 403));
    }
}
