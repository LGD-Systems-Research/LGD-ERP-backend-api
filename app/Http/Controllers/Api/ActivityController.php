<?php


namespace App\Http\Controllers\Api;


use App\Components\Activities\ActivityManager;
use Illuminate\Http\Request;

class ActivityController extends ApiController
{
    /**
     * @var \App\Components\Activities\ActivityManager
     */
    private $activityManager;

    /**
     * ActivityController constructor.
     *
     * @param \App\Components\Activities\ActivityManager $activityManager
     *
     * @since  v1.0
     * @author darryldecode <darrylfernandez.com>
     */
    public function __construct(ActivityManager $activityManager)
    {
        $this->activityManager = $activityManager;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @author darryldecode <darrylfernandez.com>
     * @since  v1.0
     */
    public function index(Request $request)
    {
        $result = $this->activityManager->listActivities($request);

        return $this->sendResponse(
            $result->getData(),
            $result->getMessage(),
            $result->getStatusCode()
        );
    }
}