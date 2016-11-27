<?php

namespace App\Models\DAO;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
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
    public function userAttributes() {
        return $this->belongsToMany('App\Models\DAO\UserAttribute',
                'nsh_userattributevalues', 'userId', 'userAttributeId')->withPivot(
                'attributeValue')->withTimestamps('createdDate', 'modifiedDate');
    }

    /**
     * The credentialTypes that this user has.
     */
    public function credentialTypes() {
        return $this->belongsToMany('App\Models\DAO\CredentialType',
                'nsh_usercredentials', 'userId', 'credentialTypeId')->withPivot(
                'password')->withTimestamps('createdDate', 'modifiedDate');
    }

    /**
     * The categories that this user belongs to.
     */
    public function categories() {
        return $this->belongsToMany('App\Models\DAO\Category',
                'nsh_users_categories_portfolio', 'userId', 'categoryId');
    }

    /**
     * The credits that this user has.
     */
    public function credits() {
        return $this->belongsToMany('App\Models\DAO\CreditType',
                'nsh_users_credits_portfolio', 'userId', 'creditTypeId')->withPivot(
                'year', 'caption')->withTimestamps('createdDate', 'modifiedDate');
    }

    /**
     * Get the images for this user.
     */
    public function images() {
        return $this->hasMany('App\Models\DAO\ImagePortfolio', 'userId');
    }

    /**
     * Get the videos for this user.
     */
    public function videos() {
        return $this->hasMany('App\Models\DAO\VideoPortfolio', 'userId');
    }

    /**
     * Get the voice clips for this user.
     */
    public function voiceClips() {
        return $this->hasMany('App\Models\DAO\VoiceClipPortfolio', 'userId');
    }

}
