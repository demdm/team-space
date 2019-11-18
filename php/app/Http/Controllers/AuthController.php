<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:100|exists:users,email',
            'password' => 'required|min:6|max:255',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'validation_errors' => $validator->errors(),
                'token' => null,
            ];
        }

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return [
                'success' => false,
                'validation_errors' => [],
                'error' => 'Invalid credentials',
                'token' => null,
            ];
        }

        return [
            'success' => true,
            'error' => null,
            'validation_errors' => [],
            'token' => $user->api_token,
        ];
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:100',
            'email' => 'required|unique:users|max:100',
            'password' => 'required|min:6|max:255',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'validation_errors' => $validator->errors(),
                'error' => null,
                'token' => null,
            ];
        }

        $apiToken = Str::random(255);
        $curDate = new \DateTime();

        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->api_token = $apiToken;
        $user->is_email_confirmed = false;
        $user->auth_status = User::AUTH_STATUS_ACTIVE;
        $user->created_at = $curDate;
        $user->updated_at = $curDate;

        if (!$user->save()) {
            return [
                'success' => false,
                'validation_errors' => [],
                'error' => 'Server error',
                'token' => null,
            ];
        }

        return [
            'success' => true,
            'validation_errors' => [],
            'error' => null,
            'token' => $apiToken,
        ];
    }

    public function resetPassword(Request $request)
    {
        $resetPasswordToken = Str::random(255);
        $request->user()->reset_password_token = $resetPasswordToken;

        if (!$request->user()->save()) {
            return [
                'success' => false,
                'error' => 'Server error',
            ];
        }

        return [
            'success' => true,
            'error' => null,
        ];
    }

    public function resetPasswordConfirmation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'new_password' => 'required|min:6|max:255',
            'reset_password_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'validation_errors' => $validator->errors(),
                'error' => null,
                'token' => null,
            ];
        }

        $user = User::where('email', $request->input('email'))
            ->where('reset_password_token', $request->input('reset_password_token'))
            ->first();

        if (!$user) {
            return [
                'success' => false,
                'error' => 'Invalid reset password url',
                'validation_errors' => [],
                'token' => null,
            ];
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->reset_password_token = null;

        if (!$user->save()) {
            return [
                'success' => false,
                'error' => 'Server error',
                'validation_errors' => [],
                'token' => null,
            ];
        }

        return [
            'success' => true,
            'error' => null,
            'validation_errors' => [],
            'token' => $user->api_token,
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->api_token = null;

        if (!$request->user()->save()) {
            return [
                'success' => false,
                'error' => 'Server error',
            ];
        }

        return [
            'success' => true,
            'error' => null,
        ];
    }
}
