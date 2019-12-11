<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $name
 * @property string $creator_id
 * @property string $owner_id
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Company extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
    ];

    public function creator(): User
    {
        return $this->hasOne(User::class, 'id', 'creator_id')->first();
    }

    public function owner(): User
    {
        return $this->hasOne(User::class, 'id', 'owner_id')->first();
    }
}
