<?php

namespace Vanguard;

use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vanguard\Support\Authorization\AuthorizationRoleTrait;

class Role extends Model
{
    use AuthorizationRoleTrait, HasFactory;

    const ROLE_ADMIN_ID = 1;
    const ROLE_USER_ID = 2;
    const ROLE_SELLER_ID = 3;
    const ROLE_SUPPLIER_ID = 4;
    const ROLE_STAFF_ID = 5;
    const ROLE_SUPPORT_ID = 5;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    protected $casts = [
        'removable' => 'boolean'
    ];

    protected $fillable = ['name', 'display_name', 'description'];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new RoleFactory;
    }
}
