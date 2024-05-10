<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'population' => 'required|string|max:255',
            'active' => 'required|boolean',
            'category_id' => 'required|numeric|exists:categories,id',
            'photo' => 'nullable|file|mimes:jpeg,jpg,png,gif',
            'birthday' => 'nullable|date',
        ];
    }

    protected function prepareForValidation()
    {
        // Check if data is present as a JSON string and parse it
        if ($this->has('data')) {
            $data = json_decode($this->input('data'), true);
            $this->merge($data);
        }
    }
}

