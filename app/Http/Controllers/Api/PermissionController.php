<?php


namespace App\Http\Controllers\Api;


use App\Components\User\Models\Permission;
use App\Components\User\PermissionManager;
use Illuminate\Http\Request;

class PermissionController extends ApiController
{
    /**
     * @var \App\Components\User\PermissionManager
     */
    private $permissionManager;

    /**
     * PermissionController constructor.
     *
     * @param \App\Components\User\PermissionManager $permissionManager
     *
     * @since  v1.0
     * @author darryldecode <darrylfernandez.com>
     */
    public function __construct(PermissionManager $permissionManager)
    {
        $this->permissionManager = $permissionManager;
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
        $result = $this->permissionManager->index($request);

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
        $result = $this->permissionManager->createPermission($request);

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
        $result = $this->permissionManager->show($id);

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
        $result = $this->permissionManager->update($request, $id);

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
        $result = $this->permissionManager->deletePermission($id);

        return $this->sendResponse(
            $result->getData(),
            $result->getMessage(),
            $result->getStatusCode()
        );
    }
}