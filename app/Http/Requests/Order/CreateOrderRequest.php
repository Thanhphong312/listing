<?php

namespace Vanguard\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends FormRequest
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
     * @return array
     */
    public function rules(Request $request)
    {
        $refId = $request->order_id;
        return [
            // 'order_id' => 'required|string',
            'ref_id' => 'unique:orders|required|string',
            'api_key' => 'required|string',
            'order_status' => 'required|string',
            'shipping_method' => 'required|string',
            'shipping_json' => 'nullable|string',
            'tracking_id' => 'nullable|string',
            'tracking_status' => 'nullable|string',
            'tracking_link' => 'nullable|string',
            'fulfill_status' => 'nullable|string',


            'override_label' => 'nullable|string',
            'note' => 'nullable|string',
            'line_items' => 'required|array',
            'line_items.*.variant_id' => 'required|integer',
            'line_items.*.product_name' => 'required|string',
            'line_items.*.quantity' => 'required|numeric',
            'line_items.*.print_files' => 'required|array',
            // 'line_items.*.mockup' => 'nullable|string|url',
            // 'line_items.*.mockup_back' => 'nullable|string|url',
            'line_items.*.mockup' => 'required_without:line_items.*.mockup_back',
            'line_items.*.mockup_back' => 'required_without:line_items.*.mockup',

            'line_items.*.print_files.*.key' => 'required|string',
            'line_items.*.print_files.*.url' => 'required|string|url',

            'shipping_label' => 'required_without:address|string',
            'address' => 'required_without:shipping_label|array',
            'address.name' => 'required_with:address|string',
            'address.phone' => 'nullable|string',
            'address.street1' => 'required_with:address|string',
            'address.street2' => 'nullable|string',
            'address.city' => 'required_with:address|string',
            'address.state' => 'required_with:address|string',
            'address.zip' => 'required_with:address|string',
            'address.country' => 'required_with:address|string',
        ];
    }
}
