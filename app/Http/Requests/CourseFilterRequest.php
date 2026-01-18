<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search'        => 'nullable|string|max:255',
            'min_price'     => 'nullable|numeric|min:0',
            'max_price'     => 'nullable|numeric|min:0',
            'owner_id'      => 'nullable|exists:users,id',
            'per_page'      => 'nullable|integer|min:1|max:100',
        ];
    }
}
