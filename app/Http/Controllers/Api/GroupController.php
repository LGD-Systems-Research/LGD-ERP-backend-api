<?php


namespace App\Http\Controllers\Api;


use App\Components\User\GroupManager;
use Illuminate\Http\Request;

class GroupController extends ApiController
{
    /**
     * @var \App\Components\User\GroupManager
     */
    private $groupManager;

    /**
     * GroupController constructor.
     *
     * @param \App\Components\User\GroupManager $groupManager
     *
     * @since  v1.0
     * @author darryldecode <darrylfernandez.com>
     */
    public function __construct(GroupManager $groupManager)
    {
        $this->groupManager = $groupManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = $this->groupManager->listGroups($request);

        return $this->sendResponse(
            $result->getData(),
            $result->getMessage(),
            $result->getStatusCode()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->groupManager->store($request);

        return $this->sendResponse(
            $result->getData(),
            $result->getMessage(),
            $result->getStatusCode()
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->groupManager->showById($id);

        return $this->sendResponse(
            $result->getData(),
            $result->getMessage(),
            $result->getStatusCode()
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = $this->groupManager->update($request, $id);

        return $this->sendResponse(
            $result->getData(),
            $result->getMessage(),
            $result->getStatusCode()
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $result = $this->groupManager->deleteGroup($id);

        return $this->sendResponse(
            $result->getData(),
            $result->getMessage(),
            $result->getStatusCode()
        );
    }
}