<?php

namespace App\Http\Controllers;

use App\Company;
use App\CompanyHasUsers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        } else {
            $result['success'] = true;
        }

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
        } else {
            $result['success'] = true;
        }

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
        } else {
            $result['success'] = true;
        }

        return $result;
    }

    public function editCompany(Request $request)
    {
        $result = [
            'success' => false,
            'validation_errors' => [],
            'error' => null,
            'data' => null,
        ];

        $validator = Validator::make($request->all(), [
            'company_name' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            $result['validation_errors'] = $this->getValidationErrors($validator);
            return $result;
        }

        /** @var User $user */
        $user = $request->user();

        $companyId = $request->get('id');
        $companyHasUsers = null;
        if ($companyId === '') {
            // create
            if (CompanyHasUsers::where('user_id', $user->id)->first()) {
                $result['error'] = 'You already related to company.';
                return $result;
            }

            $company = new Company();
            $company->id = uniqid();
            $company->owner_id = $user->id;
            $company->creator_id = $user->id;
            $company->created_at = new \DateTime();

            $companyHasUsers = new CompanyHasUsers();
            $companyHasUsers->user_id = $user->id;
            $companyHasUsers->company_id = $company->id;
        } else {
            // update

            /** @var Company $company */
            $company = Company::find($companyId);

            if (!$company) {
                $result['error'] = 'Server error';
                return $result;
            }

            if ($company->owner_id !== $user->id) {
                $result['error'] = 'Only company owner can change the company';
                return $result;
            }
        }

        $company->name = $request->get('company_name');
        $company->updated_at = new \DateTime();

        DB::beginTransaction();
        if ($company->save() && ($companyHasUsers === null || $companyHasUsers->save())) {
            $result['success'] = true;
            DB::commit();
        } else {
            $result['error'] = 'Server error';
            DB::rollBack();
        }

        return $result;
    }

    public function getData(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $company = $user->company();

        if ($company) {
            $companyCreator = [
                'id' => $company->creator_id,
                'name' => $user->id === $company->creator_id ? $user->name : $company->creator()->name,
            ];
            $companyOwner = [
                'id' => $company->owner_id,
                'name' => $user->id === $company->creator_id ? $user->name : $company->owner()->name,
            ];
            $company = [
                'id' => $company->id,
                'name' => $company->name,
                'updated_at' => date('d M. Y (H:i:s)', strtotime($company->updated_at)),
                'created_at' => date('d M. Y (H:i:s)', strtotime($company->created_at)),
            ];
        } else {
            $company = null;
            $companyCreator = null;
            $companyOwner = null;
        }

        return [
            'name' => $user->name,
            'email' => $user->email,
            'position' => $user->position,
            'role' => $user->role,
            'roles' => User::ROLES,
            'company' => $company,
            'companyCreator' => $companyCreator,
            'companyOwner' => $companyOwner,
        ];
    }
}
