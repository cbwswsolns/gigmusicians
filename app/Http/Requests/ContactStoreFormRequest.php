<?php

namespace App\Http\Requests;

use App\Rules\Captcha;

use Illuminate\Foundation\Http\FormRequest;

class ContactStoreFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        $rules = [
            'name' => 'required',
            'email' => 'required|email|confirmed',
            'message' => 'required',
            'g-recaptcha-response' => ['required', new Captcha()],
        ];

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

        $messages = [
            'name.required' => 'But what is your name?',
            'email.required' => 'We need your email address in order to reply...',
            'message.required'  => 'Are you sure that you want to contact me without saying anything? :-)',
            'g-recaptcha-response.required' => 'The reCAPTCHA field is required',
            'g-recaptcha-response' => 'Complete the reCAPTCHA to submit the form',
        ];

        return $messages;
    }
}
