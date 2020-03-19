<?php


namespace App\Components\Logger;


use App\Components\Core\BaseManager;
use App\Components\Core\Result;
use App\Components\Logger\Repositories\LogRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class LoggerManager extends BaseManager
{
    /**
     * @var \App\Components\Logger\Repositories\LogRepository
     */
    private $logRepository;

    /**
     * LoggerManager constructor.
     *
     * @param \App\Components\Logger\Repositories\LogRepository $criticalErrorLogRepository
     *
     * @since  v1.0
     * @author darryldecode <darrylfernandez.com>
     */
    public function __construct(LogRepository $criticalErrorLogRepository)
    {
        $this->logRepository = $criticalErrorLogRepository;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Components\Core\Result
     * @author darryldecode <darrylfernandez.com>
     * @since  v1.0
     */
    public function listLogs(Request $request)
    {
        $data = $this->logRepository->get(
            $request->only([
                'order_by',
                'order_sort',
                'per_page',
                'paginate',
            ]),
            [],
            function (Builder $query) use ($request) {

                if($request->has('date_start'))
                {
                    $dateStart = Carbon::parse($request->get('date_start'));
                    $query->whereDate('created_at','>=',$dateStart);
                }
                if($request->has('date_end'))
                {
                    $dateEnd = Carbon::parse($request->get('date_end'));
                    $query->whereDate('created_at','<=',$dateEnd);
                }

                return $query;
            }
        );

        return new Result(true,"List logs ok.",$data,200);
    }

    /**
     * @param string $level
     * @param string $context
     * @param string $component
     * @param array $payload
     * @param string $stacktrace
     *
     * @return \App\Components\Core\Result
     * @author darryldecode <darrylfernandez.com>
     * @since  v1.0
     */
    public function log(string $level, string $context, string $component, array $payload, string $stacktrace)
    {
        $log = $this->logRepository->create([
            'level' => $level,
            'context' => $context,
            'component' => $component,
            'payload' => $payload,
            'stacktrace' => $stacktrace,
        ]);

        if(!$log) return new Result(false,"Failed to create log.",[],400);

        return new Result(true, "Log successfully created.",$log,201);
    }
}