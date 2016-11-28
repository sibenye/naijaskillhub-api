<?php

namespace App\Models\DAO;

use Illuminate\Database\Eloquent\Model;

class VideoPortfolio extends Model {
    const CREATED_AT = 'createdDate';
    const UPDATED_AT = 'modifiedDate';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nsh_users_videos_portfolio';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [ ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = [
            'user'
    ];

    /**
     * Get the user that this video belongs to.
     */
    public function user() {
        return $this->belongsTo('App\Models\DAO\User', 'userId');
    }

}
