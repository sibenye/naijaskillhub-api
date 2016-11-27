<?php

namespace App\Models\DAO;

use Illuminate\Database\Eloquent\Model;

class UserAttributeValue extends Model {
    const CREATED_AT = 'createdDate';
    const UPDATED_AT = 'modifiedDate';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nsh_userattributevalues';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [ ];

    /**
     * Get the UserAttribute to which this UserAttributeValue belongs to.
     */
    public function UserAttribute() {
        return $this->belongsTo('App\Models\DAO\UserAttribute',
                'userAttributeId');
    }

}