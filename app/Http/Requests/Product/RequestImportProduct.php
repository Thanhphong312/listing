<?php

namespace Vanguard\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class RequestImportProduct extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function validationData()
    {
        $data = parent::validationData();

        // Remove empty values from the data
        $filteredData = array_filter($data, function ($value) {
            return !is_null($value) && $value !== '';
        });

        return $filteredData;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'file' => 'required|mimes:csv,txt',
        ];
    }
}
