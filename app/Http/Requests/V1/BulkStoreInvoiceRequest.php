<?php

namespace app\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkStoreInvoiceRequest extends FormRequest {
    public function authorize()
    {
        $user = $this->user();

        return $user != null && $user->tokenCan('create');
    }

    public function rules()
    {
        return [
            '*.customerId' => ['required', 'integer'],
            '*.amount'     => ['required', 'numeric'],
            '*.status'     => ['required', Rule::in(['B', 'P', 'V', 'b', 'v', 'p'])],
            '*.billedDate'  => ['required', 'date_format:Y-m-d H:i:s'],
            '*.paidDate'   => ['date_format:Y-m-d H:i:s', 'nullable'],
        ];
    }

    protected function prepareForValidation()
    {
        $data = [];

        foreach ($this->toArray() as $obj) {
            $obj['customer_id'] = $obj['customerId'] ?? null;
            $obj['billed_date'] = $obj['billedDate'] ?? null;
            $obj['paid_date'] = $obj['paidDate'] ?? null;

            $data[] = $obj;
        }

        $this->merge($data);
    }
}
