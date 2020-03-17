<?php


namespace App\Components\Auth;


use App\Components\Core\BaseManager;
use App\Components\Core\Result;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AuthManager extends BaseManager
{
    use AuthenticatesUsers;

    public function authenticate(Request $request)
    {
        // validate data
        $validation = validator($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
            'remember' => 'required|bool',
        ]);

        if($validation->fails()) return new Result(false, $validation->getMessageBag()->first(),[],400);

        // check throttle
        if($this->hasTooManyLoginAttempts($request))
        {
            $this->fireLockoutEvent($request);

            return new Result(false,"You have been locked out for too many login. Please try again after {$this->limiter()->availableIn($this->throttleKey($request))}",[],401);
        }

        // login
        if($this->attemptLogin($request))
        {
            $this->guard()->user()->logLastLogin();

            return new Result(false,"Authentication successful",['access_token'=>$this->guard()->user()->createToken('api_token')->plainTextToken], 200);
        }

        return new Result(false, "Invalid credentials",[],400);
    }
}