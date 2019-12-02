<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Rhumsaa\Uuid\Uuid;

class AuthController extends Controller
{
    private function getValidationErrors(\Illuminate\Validation\Validator $validator)
    {
        $validatorErrors = $validator->errors()->messages();
        $errors = [];

        foreach ($validatorErrors as $name => $validatorError) {
            if (isset($validatorError[0])) {
                $errors[$name] =  $validatorError[0];
            }
        }

        return $errors;
    }

    public function loginAsAnonymous(Request $request): array
    {
        $result = [
            'success' => false,
            'error' => null,
            'validation_errors' => [],
            'token' => null,
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            $result['validation_errors'] = $this->getValidationErrors($validator);
            return $result;
        }

        /** @var User $user */
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            $result['error'] = 'Invalid credentials';
            return $result;
        }

        if ($user->auth_status === User::AUTH_STATUS_BANNED) {
            $result['error'] = 'User banned';
            return $result;
        }

        $user->is_used = true;
        $user->is_online = true;
        $user->online_at = new \DateTime();
        $user->save();

        $result['success'] = true;
        $result['token'] = $user->api_token;
        return $result;
    }

    public function login(Request $request): array
    {
        $result = [
            'success' => false,
            'validation_errors' => [],
            'error' => null,
            'data' => null,
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|max:255',
            'password' => 'required|min:6|max:255',
        ]);

        if ($validator->fails()) {
            $result['validation_errors'] = $this->getValidationErrors($validator);
            return $result;
        }

        /** @var User $user */
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            $result['error'] = 'Invalid credentials';
            return $result;
        }

        if (!$user->password) {
            $result['error'] = 'User has no password. Set password or log in as anonymous.';
            return $result;
        }

        if (!Hash::check($request->input('password'), $user->password)) {
            $result['error'] = 'Invalid credentials';
            return $result;
        }

        if ($user->auth_status === User::AUTH_STATUS_BANNED) {
            $result['error'] = 'User banned';
            return $result;
        }

        $user->is_used = true;
        $user->is_online = true;
        $user->online_at = new \DateTime();
        $user->save();

        $result['success'] = true;
        $result['data'] = [
            'name' => $user->name,
            'token' => $user->api_token,
        ];
        return $result;
    }

    public function autoRegisterAsAnonymous(Request $request): array
    {
        $result = [
            'success' => false,
            'validation_errors' => [],
            'error' => null,
            'token' => null,
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'string|min:2|max:100',
        ]);

        if ($validator->fails()) {
            $result['validation_errors'] = $this->getValidationErrors($validator);
            return $result;
        }

        $apiToken = Uuid::uuid4()->toString();
        $curDate = new \DateTime();

        $user = new User();
        $user->id = Uuid::uuid4()->toString();
        $user->name = 'Generated name'; // todo: generate user name
        $user->email = null;
        $user->password = null;
        $user->api_token = $apiToken;
        $user->is_online = false;
        $user->is_used = true;
        $user->auth_status = User::AUTH_STATUS_ANONYMOUS;
        $user->created_at = $curDate;
        $user->updated_at = $curDate;

        if (!$user->save()) {
            $result['error'] = 'Server error';
            return $result;
        }

        $result['success'] = true;
        $result['token'] = $apiToken;
        return $result;
    }

    public function registerAsAnonymous(Request $request)
    {
        $result = [
            'success' => false,
            'validation_errors' => [],
            'error' => null,
            'token' => null,
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'string|min:2|max:100',
            'email' => 'unique:users|max:255',
            'password' => 'min:6|max:255',
        ]);

        if ($validator->fails()) {
            $result['validation_errors'] = $this->getValidationErrors($validator);
            return $result;
        }

        /** @var User $user */
        $user = $request->user();

        if ($request->input('name')) {
            $user->name = $request->input('name');
        }

        if ($request->input('email')) {
            // todo: send email with set-password-page link
            $user->email = $request->input('email');
        }

        if ($request->input('password')) {
            $user->password = Hash::make($request->get('password'));
        }

        if ($user->wasChanged() && !$user->save()) {
            $result['error'] = 'Server error';
            return $result;
        }

        $result['success'] = true;
        $result['token'] = $user->api_token;
        return $result;
    }

    public function register(Request $request): array
    {
        $result = [
            'success' => false,
            'validation_errors' => [],
            'error' => null,
            'data' => null,
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|min:6|max:255',
        ]);

        if ($validator->fails()) {
            $result['validation_errors'] = $this->getValidationErrors($validator);
            return $result;
        }

        $user = new User();
        $user->id = Uuid::uuid4()->toString();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->api_token = Uuid::uuid4()->toString();
        $user->is_online = false;
        $user->is_used = true;
        $user->auth_status = User::AUTH_STATUS_ACTIVE;
        $user->created_at = $user->updated_at = new \DateTime();

        if (!$user->save()) {
            $result['error'] = 'Server error';
            return $result;
        }

        $result['success'] = true;
        $result['data'] = [
            'name' => $user->name,
            'token' => $user->api_token,
        ];

        return $result;
    }

    public function logout(Request $request): array
    {
        /** @var User $user */
        $user = $request->user();
        $user->is_online = false;

        if (!$user->save()) {
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
