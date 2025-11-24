<?php

namespace Vanguard\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class EditStoreRequest extends FormRequest
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
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'user_id' => 'string',
            'name' => 'string',
            'type' => 'string',
            'status' => 'string',
            'api_key' => 'string',
        ];
    }
}
