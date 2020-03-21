<?php


namespace App\Components\Activities;


use App\Components\Activities\Repositories\ActivitiesRepository;
use App\Components\Core\BaseManager;
use App\Components\Core\Result;
use App\Components\Core\Utilities\Helpers;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ActivityManager extends BaseManager
{
    /**
     * @var \App\Components\Activities\Repositories\ActivitiesRepository
     */
    private $activitiesRepository;

    /**
     * ActivityManager constructor.
     *
     * @param \App\Components\Activities\Repositories\ActivitiesRepository $activitiesRepository
     *
     * @since  v1.0
     * @author darryldecode <darrylfernandez.com>
     */
    public function __construct(ActivitiesRepository $activitiesRepository)
    {
        $this->activitiesRepository = $activitiesRepository;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Components\Core\Result
     * @author darryldecode <darrylfernandez.com>
     * @since  v1.0
     */
    public function listActivities(Request $request)
    {
        $payload = $request->all();

        $results = $this->activitiesRepository->get(
            $request->only(['order_by','order_sort','per_page','paginate']),
            ['user'],
            function(Builder $query) use ($payload) {
                if(Helpers::hasValue($payload['start_date']))
                {
                    $query->whereDate('created_at','>=',Carbon::parse($payload['start_date'])->toDateString());
                }
                if(Helpers::hasValue($payload['end_date']))
                {
                    $query->whereDate('created_at','<=',Carbon::parse($payload['end_date'])->toDateString());
                }

                return $query;
            }
        );

        return new Result(true,"Get activities successful.",$results,200);
    }

    /**
     * @param string $context
     * @param string $userId
     * @param string $details
     *
     * @return \App\Components\Core\Result
     * @author darryldecode <darrylfernandez.com>
     * @since  v1.0
     */
    public function addActivity(string $context, string $userId, string $details)
    {
        $activity = $this->activitiesRepository->create([
            'user_id' => $context,
            'context' => $userId,
            'details' => $details,
        ]);

        if(!$activity) return new Result(false,"Failed to create activity.",[],400);

        return new Result(true,"Activity successfully created.",[],201);
    }
}