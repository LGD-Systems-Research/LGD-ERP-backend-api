<?php


namespace App\Http\Controllers\Api;


use App\Components\User\UserManager;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * @var \App\Components\User\UserManager
     */
    private $userManager;

    /**
     * UserController constructor.
     *
     * @param \App\Components\User\UserManager $userManager
     *
     * @since  v1.0
     * @author darryldecode <darrylfernandez.com>
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
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
        $result = $this->userManager->listUsers($request);

        return $this->sendResponse(
            $result->getData(),
            $result->getMessage(),
            $result->getStatusCode()
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @author darryldecode <darrylfernandez.com>
     * @since  v1.0
     */
    public function store(Request $request)
    {
        $result = $this->userManager->createUser($request);

        return $this->sendResponse(
            $result->getData(),
            $result->getMessage(),
            $result->getStatusCode()
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @since  v1.0
     * @author darryldecode <darrylfernandez.com>
     */
    public function update(Request $request, $id)
    {
        $result = $this->userManager->update($request, $id);

        return $this->sendResponse(
            $result->getData(),
            $result->getMessage(),
            $result->getStatusCode()
        );
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @author darryldecode <darrylfernandez.com>
     * @since  v1.0
     */
    public function show($id)
    {
        $result = $this->userManager->showById($id);

        return $this->sendResponse(
            $result->getData(),
            $result->getMessage(),
            $result->getStatusCode()
        );
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @author darryldecode <darrylfernandez.com>
     * @since  v1.0
     */
    public function delete($id)
    {
        $result = $this->userManager->destroy($id);

        return $this->sendResponse(
            $result->getData(),
            $result->getMessage(),
            $result->getStatusCode()
        );
    }
}