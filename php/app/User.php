<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $name
 * @property string $position
 * @property string $email
 * @property string $password
 * @property string $api_token
 * @property string $confirm_email_token
 * @property string $reset_password_token
 * @property string $role
 * @property string $auth_status
 * @property string $presence_status
 * @property string $work_type
 * @property bool $is_email_confirmed
 * @property bool $is_online
 * @property string|null $online_at
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class User extends Model
{
    const AUTH_STATUS_PENDING = 'pending';
    const AUTH_STATUS_ACTIVE = 'active';
    const AUTH_STATUS_BANNED = 'banned';
    const AUTH_STATUS_FIRED = 'fired';

    const ROLE_HR_MANAGER = 'hr_manager';
    const ROLE_RECRUITER = 'recruiter';
    const ROLE_EMPLOYEE = 'employee';
    const ROLES = [
        self::ROLE_HR_MANAGER => 'HR manager',
        self::ROLE_RECRUITER => 'Recruiter',
        self::ROLE_EMPLOYEE => 'Employee',
    ];

    const PRESENCE_STATUS_WORK = 'work';
    const PRESENCE_STATUS_VACATION = 'vacation';
    const PRESENCE_STATUS_SICK = 'sick';
    const PRESENCE_STATUSES = [
        self::PRESENCE_STATUS_WORK => 'at work',
        self::PRESENCE_STATUS_VACATION => 'on vacation',
        self::PRESENCE_STATUS_SICK => 'sick off',
    ];

    const WORK_TYPE_OFFICE = 'office';
    const WORK_TYPE_REMOTE = 'remote';
    const WORK_TYPE_FREELANCE = 'freelance';
    const WORK_TYPES = [
        self::WORK_TYPE_OFFICE => 'office',
        self::WORK_TYPE_REMOTE => 'remote',
        self::WORK_TYPE_FREELANCE => 'freelance',
    ];

    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'name', 'email',
    ];
    protected $hidden = [
        'password',
    ];

    public function createdCompany(): ?Company
    {
        return Company::where('creator_id', $this->id)->first();
    }

    public function ownedCompany(): ?Company
    {
        return Company::where('owner_id', $this->id)->first();
    }

    public function company(): ?Company
    {
        return ($companyHasUsers = CompanyHasUsers::where('user_id', $this->id)->first())
            ? (Company::find($companyHasUsers->company_id))
            : null;
    }
}
