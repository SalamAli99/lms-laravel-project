<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'instructor']);
    }

    public function rules(): array
    {
        return [
            'course_id'   => 'required|exists:courses,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url'   => 'nullable|url',
            'order'       => 'nullable|integer|min:1',
            'is_published'=> 'required',
            'files'       => 'sometimes|array',
            'files.*'     => 'file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ];
    }
}
