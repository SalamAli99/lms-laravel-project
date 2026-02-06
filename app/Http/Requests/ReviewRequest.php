<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    

    public function rules(): array
    {
        return [
            'user_id'        => 'integer|required',
            'course_id'        => 'integer|required',
            'sort_order'       => 'integer|required'
        ];
    }
}
