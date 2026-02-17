<?php

namespace App\Http\Requests\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class CreateCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required','exists:courses,id'],
            'code' => ['required','string','max:50','unique:coupons,code'],
            'type' => ['required','in:fixed,percent'],
            'value' => ['required','numeric','min:0'],
            'usage_limit' => ['nullable','integer','min:1'],
            'expires_at' => ['nullable','date','after:today'],
            'is_active' => ['boolean'],
        ];
    }
}
