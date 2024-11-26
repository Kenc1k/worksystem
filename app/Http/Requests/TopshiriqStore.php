<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopshiriqStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'=>'required',
            'category_id'=>'required',
            'ijrochi'=>'required',
            'file'=>'nullable|file|mimes:png,jpg,pdf,docx,jped|max:5000',
            'muddat'=>'required|date',
            'hududs'=>'required',
            'hududs.*' =>'exists:hududs,id',    
        ];
    }
}
