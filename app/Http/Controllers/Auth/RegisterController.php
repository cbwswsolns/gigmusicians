<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\Role;

use App\Models\Artists\User;

use App\Rules\Captcha;

use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */

    protected $redirectTo = '/secure';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data [array data for validation]
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            ['name' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:artist_users',
             'password' => 'required|string|min:6|confirmed',
             'g-recaptcha-response' => new Captcha(),
            ]
        );
    }


    /**
     * Create a new user instance after a valid registration and assign the standard "user" role for the new user.
     *
     * @param array $data [array data for user creation]
     *
     * @return \App\Models\Artists\User
     */
    protected function create(array $data)
    {
        $newUser = User::create(
            ['name' => $data['name'],
             'email' => $data['email'],
             'password' => Hash::make($data['password']),]
        );

        // Assign the standard "user" role for the new user
        $newUser->assignRole(Role::get()->where('name', 'user')->first());

        return $newUser;
    }
}
