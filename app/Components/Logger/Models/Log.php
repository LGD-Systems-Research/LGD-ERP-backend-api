<?php

namespace App\Components\Logger\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CriticalErrorLog
 * @package App\Components\Logger\Models
 * @author  darryldecode <darrylfernandez.com>
 * @since   v1.0
 *
 * @property int $id
 * @property string $level
 * @property string $context
 * @property string $component
 * @property array $payload
 * @property string $stacktrace
 */
class Log extends Model
{
    const CONTEXT_GENERAL = "general";
    const CONTEXT_ADMIN = "admin";
    const CONTEXT_STAFF = "staff";
    const CONTEXT_STUDENT = "student";

    const LEVEL_INFO = "info";
    const LEVEL_WARNING = "warning";
    const LEVEL_ERROR = "error";

    protected $table = 'logs';

    protected $fillable = [
        'level',
        'context',
        'component',
        'payload',
        'stacktrace',
    ];

    /**
     * @author darryldecode <darrylfernandez.com>
     * @since  v1.0
     *
     * @param $v
     */
    public function setPayloadAttribute($v)
    {
        $this->attributes['payload'] = @serialize($v);
    }

    /**
     * @author darryldecode <darrylfernandez.com>
     * @since  v1.0
     * @return mixed
     */
    public function getPayloadAttribute()
    {
        return @unserialize($this->attributes['payload']);
    }
}
