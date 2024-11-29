<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;

class BaseHostAuthController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | BaseHostAuthController
    |--------------------------------------------------------------------------
    */

    /**
     * Login user on other hosts and get token.
     * Client product hosts
     *
     * @param \Illuminate\Http\Request $request
     * @return string $token
     */
    public static function login(Request $request)
    {
        $url = config('auth.base_host.login_url');

        $request_data = $request->all();
        $data = [
            'Email' => $request_data['email'],
            'Password' => $request_data['password'],
        ];

        $response = BaseHostAuthController::curl($url, $data);

        if (isset($response->data->Message)) {
            $validator = Validator::make([], []);
            $validator->errors()->add('email', $response->data->Message);
            $validator->errors()->add('password', $response->data->Message);
            throw new ValidationException($validator);
        }

        if ($response->data->Success && strlen($response->data->Guid) > 10) {
            return $response->data->Guid;
        }

        $validator = Validator::make([], []);
        $validator->errors()->add('email', 'Sorry, we cant Login you. Try it later.');
        throw new ValidationException($validator);
    }

    /**
     * Register user on other hosts.
     * Client product hosts
     *
     * @param \Illuminate\Http\Request $request
     */
    public static function register(Request $request)
    {
        $url = config('auth.base_host.register_url');

        $request_data = $request->all();
        $data = [
            'FirstName' => trim($request_data['name']),
            'LastName' => trim($request_data['last-name']),
            'Email' => $request_data['email'],
            'Password' => $request_data['password'],
            'ConfirmPassword' => $request_data['password_confirmation'],
        ];

        $response = BaseHostAuthController::curl($url, $data);

        if (isset($response->Error)) {
            $validator = Validator::make([], []);
            $message = isset($response->Error) == 'User Exists!'
                ? 'The email has already been taken.' : $response->Error;
            $validator->errors()->add('email', $message);
            throw new ValidationException($validator);
        }

        if ($response->data == "success") {
            return BaseHostAuthController::login($request);
        }

        $validator = Validator::make([], []);
        $validator->errors()->add('name', 'Sorry, we cant register you. Try it later.');
        throw new ValidationException($validator);
    }

    /**
     * Check email exists.
     * Client product hosts
     *
     * @param \Illuminate\Http\Request $request
     */
    public static function isEmailExists(Request $request)
    {
        $url = config('auth.base_host.check_email_url');

        $request_data = $request->all();
        $data = ['Email' => $request_data['email']];

        $response = BaseHostAuthController::curl($url, $data);

        if ($response->data->Data === true) {
            return false;
        }

        if ($response->data->Data === false) {
            return true;
        }

        $validator = Validator::make([], []);
        $validator->errors()->add('email', 'Sorry, we cant Login you. Try it later.');
        throw new ValidationException($validator);
    }

    /**
     * Call rest API
     *
     * @param  array $options
     * @return JSON object
     */
    public static function curl($url, $data_array)
    {
        $header = [
            // 'Content-Type: application/x-www-form-urlencoded',
            'x-api-key: 326f47d3-c95a-455c-adab-34c6a61b8572',
        ];

        $data = [];
        foreach ($data_array as $key => $value) {
            array_push($data, urlencode($key) . '=' . urlencode($value));
        }

        $options = [
            CURLOPT_POST => true,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => join('&', $data),
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
        ];

        $curl = curl_init();
        try {
            curl_setopt_array($curl, $options);
            $response = curl_exec($curl);
            $result = json_decode($response);
        } catch (\Throwable $th) {
            throw $th;
        } finally {
            curl_close($curl);
        }

        return $result;
    }
}
