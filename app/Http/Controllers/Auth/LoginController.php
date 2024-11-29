<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

use Socialite;
use App\Models\User;
use App\ExternalToken;
use Exception;
use Validator;

class LoginController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin($request)
    {
        if (config('auth.base_host.enabled')) {
            if (!BaseHostAuthController::isEmailExists($request)) {
                $validator = Validator::make([], []);
                $validator->errors()->add('email', 'Incorrect email or password.');
                $validator->errors()->add('password', 'Incorrect email or password.');
                throw new ValidationException($validator);
            }

            $external_token = BaseHostAuthController::login($request);

            $token = ExternalToken::where('token', $external_token)->first();
            if (!isset($token) || !isset($token->user)) {
                $request_data = $request->all();
                $register_controller = new RegisterController();
                $user = $register_controller->createWithRandomData(null, null, $request_data['email']);
                $token = $register_controller->createToken($external_token, $user->id);
            }

            $request->session()->put('extt', $external_token);
            Auth::login($token->user, $request->has('remember'));

            return true;
        }

        return $this->guard()->attempt(
            $this->credentials($request), $request->has('remember')
        );
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($authProvider) {
        return Socialite::driver($authProvider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback($authProvider) {
        if (!in_array($authProvider, ['google', 'facebook', 'twitter'])) {
            return redirect('/login');
        }

        try {
            $providerUser = Socialite::driver($authProvider)->user();
        } catch (Exception $e) {
            return redirect('/login');
        }

        if ($providerUser) {
            $email = $providerUser->getEmail();
            $user = User::where('email', $email)->first();
            if (!$user) {
                $user = new User;
                $user->name = $providerUser->getName();
                $user->email = $providerUser->getEmail();
                $user->avatar = $providerUser->getAvatar();
                $user->remember_token = session('_token');
                // $user->role = 'registered';
                $user->enabled = true;
                // $user->password = 'cixrkNesCti1iP5OorpyBl3A3t3y3xPH';
                $user->save();
            }
            auth()->login($user);
            return redirect('/home');
        }
        return redirect('/login');
    }
}
