<?php

namespace Vanguard\Http\Requests\Design;

use Illuminate\Foundation\Http\FormRequest;

class DesignFilterRequest extends FormRequest
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
        // "id" => null
        // "user_code" => null
        // "niche" => null
        // "mix" => null
        // "tag" => null
        // "created_at" => null
        return [
            'id' => 'nullable|integer',
            'user_code' => 'nullable|string|max:255',
            'user_id' => 'nullable|int|exists:users,id',
            'niche' => 'nullable|string|max:255',
            'mix' => 'nullable|string|max:255',
            'tag' => 'nullable|string|max:255',
            'created_at' => 'nullable|date_format:Y-m-d',
        ];
    }
}
