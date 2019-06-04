<?php

namespace App\Http\Requests\Artists;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class ProfileStoreFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        $rules['name']  = 'required|string';

        $rules['email'] = 'required|email|unique:artist_profiles';

        $rules['category_id'] = ['required', Rule::notIn(['None'])];

        $rules['description'] = 'required';

        $rules['images'] = 'required|max:2';

        $rules['images.*'] = 'mimes:jpeg,bmp,png|max:10000';

        $rules['musicfiles'] = 'required_without_all:soundplatform,youtube|max:2';

        $rules['musicfiles.*'] = 'mimes:mp3,mpga,wav|max:10000';

        $rules['soundplatform'] = 'nullable|url|required_without_all:musicfiles,youtube';

        $rules['youtube'] = ['nullable','url','regex:/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/','required_without_all:musicfiles,soundplatform'];

        $rules['tsandcs'] = 'required';

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

        $messages['name.required'] = 'Name of Artist/Group is required.';

        $messages['email.required'] = 'Contact Email Address is required.';

        $messages['category_id.not_in'] = 'Musical Genre is required.';

        $messages['description.required'] = 'Description of Artist/Music/Group/Act is required.';

        $messages['images.required'] = 'At least one image of Artist/Group is required for the profile.';

        $messages['images.max'] = 'A maximum of two files are permitted! (including existing attached image files)';

        $messages['images.*.mimes'] = 'Wrong file type.';
        $messages['images.*.max'] = 'The image file size must be less than 10 MB.';

        $messages['musicfiles.required_without_all'] = 'The Sample Tracks field is required if both the Sound Platform and YouTube link fields are left blank.';

        $messages['musicfiles.max'] = 'A maximum of two files are permitted! (including existing attached music files)';

        $messages['musicfiles.*.mimes'] = 'Wrong file type.';
        $messages['musicfiles.*.max'] = 'The Sample Tracks file size must be less than 10 MB.';

        $messages['soundplatform.required_without_all'] = 'The Sound Platform field is required if both the Sample Tracks and YouTube link fields are left blank.';

        $messages['youtube.required_without_all'] = 'The YouTube link field is required if both the Sample Tracks and Sound Platform link fields are left blank.';

        $messages['tsandcs.required'] = 'You must agree to comply with our Terms and Conidtions before submitting your profile';

        return $messages;
    }
}
