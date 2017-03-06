<?php
namespace App\Models\DAO;

use Illuminate\Database\Eloquent\Model;

class UserAttributeType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nsh_userattributetypes';

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
}
