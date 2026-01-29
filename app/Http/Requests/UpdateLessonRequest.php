<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'instructor']);
    }

    public function rules(): array
    {
        return [
            'title'        => 'sometimes|string|max:255',
            'description'  => 'nullable|string',
            'video_url'    => 'nullable|url',
            'order'        => 'nullable|integer|min:1',
            'is_published'=> 'boolean',
            'files'       => 'sometimes|array',
            'files.*'     => 'file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ];
    }
}
