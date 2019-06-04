<?php

namespace App\Http\Requests\Artists;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasRole('admin');
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        $rules['name'] = 'required|unique:artist_categories';

        return $rules;
    }


    /**
     * Get the messages corresponding to the validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [];

        $messages['name.required'] = 'The Musical Genre / Category field is required';
        $messages['name.unique'] = 'The Musical Genre / Category name already exists!';

        return $messages;
    }
}
