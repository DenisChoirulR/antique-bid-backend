<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ItemRequest extends FormRequest
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
        $method = $this->method();

        if ($method == 'PUT') {
            return [
                'name' => 'nullable|string|max:255',
                'slug' => [
                    'nullable',
                    'regex:/^[a-z0-9-]+$/',
                    Rule::unique('items')->ignore($this->route('id'))
                ],
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'nullable|string',
                'starting_price' => 'nullable|numeric|min:0',
                'start_time' => 'nullable|date_format:Y-m-d H:i',
                'end_time' => 'nullable|date_format:Y-m-d H:i|after:start_time',
            ];
        } else {
            return [
                'name' => 'required|string|max:255',
                'slug' => [
                    'required',
                    'regex:/^[a-z0-9-]+$/',
                    'unique:items,slug',
                ],
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'required|string',
                'starting_price' => 'required|numeric|min:0',
                'start_time' => 'required|date_format:Y-m-d H:i',
                'end_time' => 'required|date_format:Y-m-d H:i|after:start_time',
            ];
        }
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], 403));
    }
}
