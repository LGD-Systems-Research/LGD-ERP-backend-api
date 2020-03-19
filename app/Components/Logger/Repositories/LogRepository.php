<?php
/**
 * Created by PhpStorm.
 * User: darryldecode
 * Date: 6/29/2018
 * Time: 1:52 AM
 */

namespace App\Components\Logger\Repositories;


use App\Components\Core\BaseRepository;
use App\Components\Logger\Models\Log;
use Carbon\Carbon;

class LogRepository extends BaseRepository
{
    /**
     * CriticalErrorLogRepository constructor.
     *
     * @param \App\Components\Logger\Models\Log $model
     *
     *@since  v1.0
     *
     * @author darryldecode <darrylfernandez.com>
     */
    public function __construct(Log $model)
    {
        parent::__construct($model);
    }

    /**
     * delete old data
     *
     * @author darryldecode <darrylfernandez.com>
     * @since  v1.0
     *
     * @param int $olderThanDays
     *
     * @return int
     */
    public function deleteOldData($olderThanDays = 60)
    {
        $date = Carbon::now();

        $q = $this->getModel()->where('created_at','<=',$date->subDays($olderThanDays));

        $count = $q->count();

        $q->delete();

        return $count;
    }
}