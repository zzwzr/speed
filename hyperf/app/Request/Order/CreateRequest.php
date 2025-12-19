<?php

declare(strict_types=1);

namespace App\Request\Order;

use Hyperf\Validation\Request\FormRequest;

class CreateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'address_id'        => 'required',
            'goods'             => 'required|array',
            'goods.*.id'        => 'required|integer',
            'goods.*.number'    => 'required|integer'
        ];
    }
}
