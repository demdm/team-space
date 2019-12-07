<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
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

    public function editName(Request $request)
    {
        $result = [
            'success' => false,
            'validation_errors' => [],
            'error' => null,
            'data' => null,
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'string|min:2|max:100',
        ]);

        if ($validator->fails()) {
            $result['validation_errors'] = $this->getValidationErrors($validator);
            return $result;
        }

        /** @var User $user */
        $user = $request->user();
        $user->name = $request->input('name');

        if (!$user->save()) {
            $result['error'] = 'Server error';
            return $result;
        }

        $result['success'] = true;

        return $result;
    }

    public function editEmail(Request $request)
    {
        $result = [
            'success' => false,
            'validation_errors' => [],
            'error' => null,
            'data' => null,
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users|max:255',
        ]);

        if ($validator->fails()) {
            $result['validation_errors'] = $this->getValidationErrors($validator);
            return $result;
        }

        /** @var User $user */
        $user = $request->user();
        $user->email = $request->input('email');

        if (!$user->save()) {
            $result['error'] = 'Server error';
            return $result;
        }

        $result['success'] = true;

        return $result;
    }

    public function editPassword(Request $request)
    {
        $result = [
            'success' => false,
            'validation_errors' => [],
            'error' => null,
            'data' => null,
        ];

        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6|max:255',
        ]);

        if ($validator->fails()) {
            $result['validation_errors'] = $this->getValidationErrors($validator);
            return $result;
        }

        /** @var User $user */
        $user = $request->user();
        $user->password = Hash::make($request->get('password'));

        if (!$user->save()) {
            $result['error'] = 'Server error';
            return $result;
        }

        $result['success'] = true;

        return $result;
    }

    public function editPosition(Request $request)
    {
        $result = [
            'success' => false,
            'validation_errors' => [],
            'error' => null,
            'data' => null,
        ];

        $validator = Validator::make($request->all(), [
            'role' => [
                'required',
                Rule::in(array_keys(User::ROLES)),
            ],
            'position' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            $result['validation_errors'] = $this->getValidationErrors($validator);
            return $result;
        }

        /** @var User $user */
        $user = $request->user();
        $user->position = $request->get('position');
        $user->role = $request->get('role');

        if (!$user->save()) {
            $result['error'] = 'Server error';
            return $result;
        }

        $result['success'] = true;

        return $result;
    }

    public function getData(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return [
            'name' => $user->name,
            'email' => $user->email,
            'position' => $user->position,
            'role' => $user->role,
            'roles' => User::ROLES,
        ];
    }
}
