<?php

namespace Vanguard\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
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
            'name' => 'nullable|string',
            'name' => 'nullable|string',
            'price' => 'nullable|string',
            'sku' => 'nullable|string',
            'style' => 'nullable|string',
            'color' => 'nullable|string',
            'size' => 'nullable|string',
            'stock' => 'nullable|integer',
            'mockup_src' => 'nullable|string',
            'weight' => 'nullable|integer',
            'length' => 'nullable|integer',
            'width' => 'nullable|integer',
            'height' => 'nullable|integer',
            'brand' => 'nullable|string',
            'warehouse_name' => 'nullable|string',
        ];
    }
}
