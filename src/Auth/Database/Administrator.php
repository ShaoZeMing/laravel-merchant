<?php

namespace ShaoZeMing\Merchant\Auth\Database;

use ShaoZeMing\Merchant\Traits\AdminBuilder;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use App\Entities\Merchant;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Administrator extends Model implements AuthenticatableContract
{
    use Authenticatable, AdminBuilder, HasPermissions;

    protected $fillable = ['mobile','email', 'password', 'name', 'avatar','merchant_id','user_type'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('merchant.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('merchant.database.users_table'));

        parent::__construct($attributes);
    }


    public function merchant(){
        return $this->belongsTo(Merchant::class);
    }
}
