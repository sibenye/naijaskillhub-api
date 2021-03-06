<?php

namespace App\Models\DAO;

use Illuminate\Database\Eloquent\Model;

class CredentialType extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nsh_credentialtypes';

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
     * The users that have this credentialType.
     */
    public function users() {
        return $this->belongsToMany('App\Models\DAO\User',
                'nsh_usercredentials', 'credentialTypeId', 'userId')->withPivot(
                'password')->withTimestamps('createdDate', 'modifiedDate');
    }

}
