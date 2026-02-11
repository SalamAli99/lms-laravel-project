<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'in:pending,active,completed,cancelled'
            ],
            'course_id' => [
                'required',
                'exists:courses,id',
            ],
        ];
    }
}
