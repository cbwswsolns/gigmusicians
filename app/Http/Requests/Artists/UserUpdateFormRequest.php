<?php

namespace App\Http\Requests\Artists;

use App\Models\Artists\User;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateFormRequest extends FormRequest
{
    /**
     * The hasher instance
     *
     * @var HasherContract
     */
    protected $hasher;


    /**
     * The user model instance
     *
     * @var \App\Models\Artists\User
     */
    protected $user;


    /**
     * Create a new form request instance.
     *
     * @param HasherContract           $hasher [the hasher instance]
     * @param \App\Models\Artists\User $user   [the user model instance]
     *
     * @return void
     */
    public function __construct(HasherContract $hasher, User $user)
    {
        $this->hasher = $hasher;
        $this->user = $user;
    }


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
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password_current' => 'required|string|min:6',
            'password' => 'required|string|min:6|confirmed'
        ];
    }


    /**
     * Configure the validator instance - verify that this update is really being made by the authenticated user
     *
     * @param \Illuminate\Validation\Validator $validator [the validator instance]
     *
     * @return void
     */
    public function withValidator($validator)
    {
        // Note: this hasher password check is a safeguard against third party alteration to user account
        // (especially in the case where the user has forgotten to log out and the session is still active)

        $validator->after(
            function ($validator) {
                $user = $this->route('user');

                (auth()->user()->isAdmin()) ? $pass = auth()->user()->password : $pass = $this->user->findOrFail($user->id)->password;

                if (isset($this->password_current) && !$this->hasher->check($this->password_current, $pass)) {
                    $validator->errors()->add('password_current', 'Your current password is incorrect.');
                }
            }
        );
    }
}
