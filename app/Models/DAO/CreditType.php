<?php

namespace App\Models\DAO;

use Illuminate\Database\Eloquent\Model;

class CreditType extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nsh_credittypes';

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
     * The users to which this creditType belongs to.
     */
    public function users() {
        return $this->belongsToMany('App\Models\DAO\User',
                'nsh_users_credits_portfolio', 'creditTypeId', 'userId')->withPivot(
                'year', 'caption')->withTimestamps('createdDate', 'modifiedDate');
    }

}
