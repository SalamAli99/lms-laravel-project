<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasAnyRole(['admin']);
    }

    public function rules(): array
    {
        $rules =[
            'title' => 'required|string|max:255',
            'description' => 'required|string',
                'is_paid' => ['required','boolean'],
                'price' => ['nullable','numeric','min:0','required_if:is_paid,1'],
                'user_id'=>'required'
        ];
        return $rules;
    }
    public function messages(): array
    {
        return [
            'images.*.image' => 'Each image must be a valid image file.',
            'images.*.mimes' => 'Images must be JPG, JPEG, PNG, or GIF.',
            'images.*.max'   => 'Each image cannot exceed 5MB.',
            'files.*.file'   => 'Each file must be a valid file.',
            'files.*.mimes'  => 'Files must be PDF.',
            'files.*.max'    => 'Each file cannot exceed 10MB.',
        ];
    }
}
