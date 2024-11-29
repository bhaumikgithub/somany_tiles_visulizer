<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Exception;
use App\ExternalToken;

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
    protected $redirectTo = '/home';

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
            'name' => 'required|max:64',
            'last-name' => 'required|max:64',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|max:255|string|confirmed',
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = null;
        if (config('auth.base_host.enabled')) {
            $external_token = BaseHostAuthController::register($request);
            $request->session()->put('extt', $external_token);

            $request_data = $request->all();
            $user = $this->createWithRandomData($request_data['name'], $request_data['last-name'], $request_data['email'], $request_data['password']);
            $this->createToken($external_token, $user->id);
        } else {
            $user = $this->create($request->all());
        }

        event(new Registered($user));

        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    public function createWithRandomData($name, $last_name = null, $email = null, $password = null)
    {
        if (isset($email)) {
            $user = User::where('email', $email)->first();
            if (isset($user)) return $user;
        } else {
            $email = $this->generateEmail();
        }

        if (!isset($name)) $name = 'User';
        if (!isset($last_name)) $last_name = 'profile';
        if (!isset($password)) $password = $this->generateRandomString(32);

        $data = [
            'name' => $name,
            'last-name' => $last_name,
            'email' => $email,
            'password' => $password,
        ];

        return $this->create($data);
    }

    public function createToken($user_external_token, $user_id)
    {
        $token = ExternalToken::where('token', $user_external_token)->first();
        if (!isset($token)) $token = new ExternalToken();

        $token->user_id = $user_id;
        $token->token = $user_external_token;
        $token->save();

        return $token;
    }

    private function generateEmail() {
        $email = '';
        do {
            $email = $this->generateRandomString(32);
        } while (sizeof(User::where('email', '=', $email)->get()) > 0);

        return $email;
    }

    private function generateRandomString($length = 32) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen($characters);
        $random_string = '';
        for ($i = 0; $i < $length; $i++) {
            $random_string .= $characters[rand(0, $characters_length - 1)];
        }
        return $random_string;
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'] . ' ' . $data['last-name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
