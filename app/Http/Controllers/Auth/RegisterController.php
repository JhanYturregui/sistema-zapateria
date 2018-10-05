<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
    protected $redirectTo = '/';

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
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'doc_num' => 'required|string|max:8',
            'username' => 'required|string|unique:usuarios',
            'password' => 'required|string|min:6|confirmed',
        ],[
            'doc_num.max' => '8 dígitos como máximo',
            'username.unique' => 'Este usuario ya está siendo usado',
            'password.confirmed' => 'Las contraseñas no son iguales'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'num_documento' => $data['doc_num'],
            'username'      => $data['username'],
            'password'      => bcrypt($data['password']),
            'tipo'          => 1,
            'estado'        => true
        ]);
    }
}
