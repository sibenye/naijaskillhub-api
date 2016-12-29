<?php
namespace App\Models\DAO;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nsh_accounttypes';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [ ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The users that this accountType belongs to.
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\DAO\User', 'nsh_users_accounttypes_link',
                'accountTypeId', 'userId');
    }
}
