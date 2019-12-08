<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $company_id
 * @property string $user_id
 */
class CompanyHasUsers extends Model
{
    protected $primaryKey = false;
    public $incrementing = false;
    public $created_at = null;
    public $updated_at = null;

    protected $fillable = [
        'user_id',
        'company_id',
    ];
}
