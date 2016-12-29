<?php
namespace App\Models\DAO;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    const CREATED_AT = 'createdDate';
    const UPDATED_AT = 'modifiedDate';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nsh_users';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [ ];

    /**
     * The userAttributes that belong to this user.
     */
    public function userAttributes()
    {
        return $this->belongsToMany('App\Models\DAO\UserAttribute', 'nsh_userattributevalues',
                'userId', 'userAttributeId')->withPivot('attributeValue')->withTimestamps(
                'createdDate', 'modifiedDate');
    }

    /**
     * The credentialTypes that this user has.
     */
    public function credentialTypes()
    {
        return $this->belongsToMany('App\Models\DAO\CredentialType', 'nsh_usercredentials',
                'userId', 'credentialTypeId')->withPivot('password')->withTimestamps('createdDate',
                'modifiedDate');
    }

    /**
     * The categories that this user belongs to.
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\DAO\Category', 'nsh_users_categories_link',
                'userId', 'categoryId');
    }

    /**
     * The accountTypes that this user has.
     */
    public function accountTypes()
    {
        return $this->belongsToMany('App\Models\DAO\AccountType', 'nsh_users_accounttypes_link',
                'userId', 'accountTypeId');
    }

    /**
     * The credits that this user has.
     */
    public function credits()
    {
        return $this->belongsToMany('App\Models\DAO\CreditType', 'nsh_users_credits_portfolio',
                'userId', 'creditTypeId')->withPivot('id', 'year', 'caption')->withTimestamps(
                'createdDate', 'modifiedDate');
    }

    /**
     * Get the images for this user.
     */
    public function images()
    {
        return $this->hasMany('App\Models\DAO\ImagePortfolio', 'userId');
    }

    /**
     * Get the videos for this user.
     */
    public function videos()
    {
        return $this->hasMany('App\Models\DAO\VideoPortfolio', 'userId');
    }

    /**
     * Get the audio clips for this user.
     */
    public function audios()
    {
        return $this->hasMany('App\Models\DAO\AudioPortfolio', 'userId');
    }
}
