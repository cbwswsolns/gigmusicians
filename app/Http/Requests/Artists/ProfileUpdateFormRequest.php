<?php

namespace App\Http\Requests\Artists;

use App\Rules\MaxImageFileCount;
use App\Rules\MaxMusicFileCount;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class ProfileUpdateFormRequest extends FormRequest
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

        $profile = $this->route('profile');

        $rules['name']  = 'required|string';

        // A profile has to have a unique email that differs from that of all other profiles
        $rules['email'] = ['required', 'email', Rule::unique('artist_profiles')->ignore($profile->id)];

        $rules['category_id'] = 'required';

        $rules['description'] = 'required';

        // Get a count of all images in the database
        $imageDbCount = $profile->images()->count();

        // The images field is only required if there are no image file exists in the database
        if ($imageDbCount == 0) {
            $rules['images'] = ['required', new MaxImageFileCount($profile)];
        }
        
        // Apply file limit count if at least one image file exists in the database
        if ($imageDbCount > 0) {
            $rules['images'] = [new MaxImageFileCount($profile)];
        }

        $rules['images.*'] = 'mimes:jpeg,bmp,png|max:10000';

        $musicFileDbCount = $profile->musicfiles()->count();

        // No music files in the database? At least one of the following fields is required
        if ($musicFileDbCount == 0) {
            $rules['musicfiles'] = ['required_without_all:soundplatform,youtube', new MaxMusicFileCount($profile)];
            $rules['soundplatform'] = 'nullable|url|required_without_all:musicfiles,youtube';
            $rules['youtube'] = ['nullable','url','regex:/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/','required_without_all:musicfiles,soundplatform'];
        }

        // More than zero files in the database? All of the following fields are optional
        if ($musicFileDbCount > 0) {
            $rules['musicfiles'] = [new MaxMusicFileCount($profile)];
            $rules['soundplatform'] = 'nullable|url';
            $rules['youtube'] = ['nullable','url','regex:/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/'];
        }

        $rules['musicfiles.*'] = 'mimes:mp3,mpga,wav|max:10000';

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
    
        $messages['images.*.mimes'] = 'Wrong file type.';
        $messages['images.*.max'] = 'The image size must be less than 10 MB.';

        $messages['musicfiles.required_without_all'] = 'The Sample Tracks field is required if both the Sound Platform and YouTube link fields are left blank.';
        
        $messages['musicfiles.*.mimes'] = 'Wrong file type.';
        $messages['musicfiles.*.max'] = 'The Sample Tracks file size must be less than 10 MB.';

        $messages['soundplatform.required_without_all'] = 'The Sound Platform field is required if both the Sample Tracks and YouTube link fields are left blank.';

        $messages['youtube.required_without_all'] = 'The YouTube link field is required if both the Sample Tracks and Sound Platform link fields are left blank.';

        return $messages;
    }
}
