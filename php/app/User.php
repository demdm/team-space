<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

/**
 * @property string $id
 * @property string $name
 * @property string|null $email
 * @property string|null $password
 * @property string|null $api_token
 * @property string $auth_status
 * @property bool $is_online
 * @property bool $is_used
 * @property string|null $online_at
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    const AUTH_STATUS_ANONYMOUS = 'anonymous';
    const AUTH_STATUS_ACTIVE = 'active';
    const AUTH_STATUS_BANNED = 'banned';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
}
