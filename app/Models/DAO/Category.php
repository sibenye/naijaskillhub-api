<?php
namespace App\Models\DAO;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nsh_categories';

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
     * The users that this category belongs to.
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\DAO\User', 'nsh_users_categories_link',
                'categoryId', 'userId');
    }
}
