<?php

namespace App\Http\Controllers;

use App\Company;
use App\CompanyHasUsers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        if (!Hash::check($request->input('password'), $user->password)) {
            $result['error'] = 'Invalid credentials';
            return $result;
        }

        if ($user->auth_status === User::AUTH_STATUS_BANNED) {
            $result['error'] = 'User banned';
            return $result;
        }

        if ($user->auth_status === User::AUTH_STATUS_FIRED) {
            $result['error'] = 'User fired';
            return $result;
        }

        $user->is_online = true;
        $user->online_at = new \DateTime();

        if ($user->save()) {
            $result['success'] = true;
            $result['data'] = [
                'name' => $user->name,
                'token' => $user->api_token,
            ];
        } else {
            $result['error'] = 'Server error';
        }

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
        $user->position = '';
        $user->api_token = md5(uniqid($request->get('email') . time(), true));
        $user->role = User::ROLE_EMPLOYEE;
        $user->auth_status = User::AUTH_STATUS_ACTIVE;
        $user->presence_status = User::PRESENCE_STATUS_WORK;
        $user->work_type = User::WORK_TYPE_OFFICE;
        $user->is_email_confirmed = false;
        $user->is_online = false;
        $user->created_at = new \DateTime();
        $user->updated_at = new \DateTime();

        $companyHasUsers = null;
        $companyId = $request->get('company_id');

        if ($companyId) {
            if (!Company::find($companyId)) {
                $result['error'] = 'Company not found';
                return $result;
            }

            $companyHasUsers = new CompanyHasUsers();
            $companyHasUsers->user_id = $user->id;
            $companyHasUsers->company_id = $companyId;
        }

        DB::beginTransaction();
        if ($user->save() && ($companyHasUsers === null || $companyHasUsers->save())) {
            $result['success'] = true;
            $result['data'] = [
                'id' => $user->id,
                'name' => $user->name,
                'token' => $user->api_token,
            ];
            DB::commit();
        } else {
            $result['error'] = 'Server error';
            DB::rollBack();
        }

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
