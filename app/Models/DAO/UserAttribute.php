<?php

namespace App\Models\DAO;

use Illuminate\Database\Eloquent\Model;

class UserAttribute extends Model {
    const CREATED_AT = 'createdDate';
    const UPDATED_AT = 'modifiedDate';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nsh_userattributes';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [ ];

}
