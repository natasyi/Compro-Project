<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAboutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Sesuaikan dengan logika otorisasi yang kamu inginkan
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'thumbnail' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
            'type' => ['required', 'in:Visions,Missions'],
            'keypoint' => ['nullable', 'array', 'max:5'], // Mengizinkan hingga 5 keypoint
        ];
    }
}
