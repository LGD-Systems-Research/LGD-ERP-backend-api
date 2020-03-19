<?php
/**
 * Created by PhpStorm.
 * User: darryldecode
 * Date: 4/28/2018
 * Time: 12:52 AM
 */

namespace App\Components\Announcement\Models;


use App\Components\Core\Utilities\Helpers;
use App\Components\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Announcement extends Model
{
    protected $table = 'announcements';

    protected $fillable = [
        'author_id',
        'title',
        'content',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class,'author_id');
    }
}