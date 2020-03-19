<?php
/**
 * Created by PhpStorm.
 * User: darryldecode
 * Date: 3/10/2018
 * Time: 3:51 PM
 */

namespace App\Components\Activities\Models;


use App\Components\User\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Activity
 * @package App\Components\Activities\Models
 * @author  darryldecode <darrylfernandez.com>
 * @since   v1.0
 */
class Activity extends Model
{
    const CONTEXT_GENERAL = "general";
    const CONTEXT_ADMIN = "admin";
    const CONTEXT_STAFF = "staff";
    const CONTEXT_STUDENT = "student";

    protected $table = 'activities';

    protected $fillable = [
        'user_id',
        'context',
        'details',
    ];

    protected $appends = ['ago'];

    #region relation
    /**
     * the user who perform the activity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    #region accessors
    public function getAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}