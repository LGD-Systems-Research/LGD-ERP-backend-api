<?php


namespace App\Http\Controllers\Api;


use App\Components\Auth\AuthManager;
use Illuminate\Http\Request;

class AuthController extends ApiController
{
    /**
     * @var \App\Components\Auth\AuthManager
     */
    private $authManager;

    /**
     * AuthController constructor.
     *
     * @param \App\Components\Auth\AuthManager $authManager
     *
     * @since  v1.0
     * @author darryldecode <darrylfernandez.com>
     */
    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @author darryldecode <darrylfernandez.com>
     * @since  v1.0
     */
    public function authenticate(Request $request)
    {
        /** @var \App\Components\Core\Result $result */
        $result = $this->authManager->authenticate($request);

        return $this->sendResponse($result->getData(), $result->getMessage(), $result->getStatusCode());
    }
}