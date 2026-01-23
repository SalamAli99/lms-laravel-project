<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'instructor']);
    }

    public function rules(): array
    {
        $rules =[
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            
        ];
// Only admins can manage files/images
        if ($this->user()->hasRole('admin')) {
            $rules = array_merge($rules, [
                'images'   => ['nullable', 'array', 'max:5'],
                'images.*' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:5120'],

                'files'    => ['nullable', 'array', 'max:3'],
                'files.*'  => ['file', 'mimes:pdf', 'max:10240'],
            ]);
        }

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
