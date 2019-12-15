<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    const MAX_PER_PAGE = 50;

    public function list(Request $request)
    {
        $page = (int) $request->request->get('page');
        $page = !$page || $page < 1 ? 1 : $page;

        /** @var User $user */
        $user = Auth::user();
        $userCompany = $user->company();
        $employeeList = [];
        $employeeListCount = 0;

        $sortFields = [
            'created_at_desc' => 'Last created',
            'updated_at_desc' => 'Last updated',
            'online_at_desc' => 'Last online',
        ];

        $data = [
            'per_page' => 10,
            'page' => $page,
            'name' => '',
            'email' => '',
            'role' => '',
            'presence_status' => '',
            'work_type' => '',
            'sort_field' => '',
        ];

        if ($userCompany) {
            $employeeListQuery = DB::table('users')
                ->join(
                    'company_has_users',
                    'users.id',
                    '=',
                    'company_has_users.user_id')
                ->where(
                    'company_has_users.company_id',
                    $userCompany->id
                );

            $name = $request->request->get('name');
            if ($name) {
                $data['name'] = $name;
                $employeeListQuery->where('name', 'like', "%$name%");
            }

            $email = $request->request->get('email');
            if ($email) {
                $data['email'] = $email;
                $employeeListQuery->where('email', 'like', "%$email%");
            }

            $role = $request->request->get('role');
            if (in_array($role, array_keys(User::ROLES))) {
                $data['role'] = $role;
                $employeeListQuery->where('role', $role);
            }

            $presenceStatus = $request->request->get('presence_status');
            if (in_array($presenceStatus, array_keys(User::PRESENCE_STATUSES))) {
                $data['presence_status'] = $presenceStatus;
                $employeeListQuery->where('presence_status', $presenceStatus);
            }

            $workType = $request->request->get('work_type');
            if (in_array($workType, array_keys(User::WORK_TYPES))) {
                $data['work_type'] = $workType;
                $employeeListQuery->where('work_type', $workType);
            }

            $employeeListCount = $employeeListQuery->count('users.id');

            $sortField = $request->request->get('sort_field');
            $sortField = $sortField === '' || !in_array($sortField, array_keys($sortFields)) ? 'created_at_desc' : $sortField;
            $data['sort_field'] = $sortField;

            switch ($sortField) {
                case 'created_at_desc':
                    $employeeListQuery->orderBy('created_at', 'desc');
                    break;
                case 'updated_at_desc':
                    $employeeListQuery->orderBy('updated_at', 'desc');
                    break;
                case 'online_at_desc':
                    $employeeListQuery->orderBy('online_at', 'desc');
                    break;
                default:
                    throw new \Exception('Unknown sort.');
            }

            $perPage = (int) $request->request->get('per_page');
            $perPage = $perPage > self::MAX_PER_PAGE ? self::MAX_PER_PAGE : ($perPage < 1 ? 1 : $perPage);
            $data['per_page'] = $perPage;

            $employeeList = $employeeListQuery
                ->select(
                    'users.id',
                    'users.name',
                    'users.position',
                    'users.email',
                    'users.role',
                    'users.auth_status',
                    'users.presence_status',
                    'users.work_type',
                    'users.is_online',
                    'users.online_at'
                )
                ->limit($perPage)
                ->offset($page - 1)
                ->get();
        }

        return [
            'list' => $employeeList,
            'list_count' => $employeeListCount,
            'total_pages' => $employeeListCount > 0
                ? ceil($employeeListCount / $page)
                : 0,
            'roles' => User::ROLES,
            'presence_statuses' => User::PRESENCE_STATUSES,
            'work_types' => User::WORK_TYPES,
            'sort_fields' => $sortFields,
            'data' => $data,
        ];
    }
}
