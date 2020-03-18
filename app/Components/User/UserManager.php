<?php


namespace App\Components\User;


use App\Components\Core\BaseManager;
use App\Components\Core\Result;
use App\Components\Core\Utilities\Helpers;
use App\Components\User\Models\User;
use App\Components\User\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserManager extends BaseManager
{
    /**
     * @var \App\Components\User\Repositories\UserRepository
     */
    private $userRepository;

    /**
     * UserManager constructor.
     *
     * @param \App\Components\User\Repositories\UserRepository $userRepository
     *
     * @since  v1.0
     * @author darryldecode <darrylfernandez.com>
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Components\Core\Result
     * @since  v1.0
     * @author darryldecode <darrylfernandez.com>
     */
    public function listUsers(Request $request)
    {
        $data = $this->userRepository->listUsers($request->all());

        return new Result(true,"List users successful.",$data,200);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Components\Core\Result
     * @author darryldecode <darrylfernandez.com>
     * @since  v1.0
     */
    public function createUser(Request $request)
    {
        $validate = validator($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'permissions' => 'array',
            'groups' => 'array',
        ]);

        if($validate->fails()) return new Result(false,$validate->errors()->first(),[],400);

        /** @var User $user */
        $user = $this->userRepository->create($request->all());

        if(!$user) return new Result(false,"Failed create user account.",[],400);

        // attach to group
        if($groups = $request->get('groups',[]))
        {
            foreach ($groups as $groupId => $shouldAttach)
            {
                if($shouldAttach) $user->groups()->attach($groupId);
            }
        }

        return new Result(true,"User account created.",$user,201);
    }

    /**
     * @param $id
     * @param array $with
     *
     * @return \App\Components\Core\Result
     * @since  v1.0
     * @author darryldecode <darrylfernandez.com>
     */
    public function showById($id, $with = [])
    {
        $with = array_merge($with,['groups']);

        $user = $this->userRepository->find($id,$with);

        if(!$user) return new Result(false,"User not found",[],404);

        return new Result(true,"User found.",$user,200);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return \App\Components\Core\Result
     * @since  v1.0
     * @author darryldecode <darrylfernandez.com>
     */
    public function update(Request $request, $id)
    {
        $validate = validator($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'permissions' => 'array',
            'groups' => 'array',
        ]);

        if($validate->fails()) return new Result(false,$validate->errors()->first(),[],400);

        $payload = $request->all();

        // if password field is present but has empty value or null value
        // we will remove it to avoid updating password with unexpected value
        if(!Helpers::hasValue($payload['password'])) unset($payload['password']);

        $updated = $this->userRepository->update($id,$payload);

        if(!$updated) return new Result(false,"Failed to update user.",[],400);

        // re-sync groups
        /** @var User $user */
        $user = $this->userRepository->find($id);

        $groupIds = [];

        if($groups = $request->get('groups',[]))
        {
            foreach ($groups as $groupId => $shouldAttach)
            {
                if($shouldAttach) $groupIds[] = $groupId;
            }
        }

        $user->groups()->sync($groupIds);

        return new Result(true,"User successfully updated.",[],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \App\Components\Core\Result
     */
    public function destroy($id)
    {
        // do not delete self
        if($id == auth()->user()->id) return new Result(false,"Forbidden action.",[],401);

        try {
            $this->userRepository->delete($id);
        } catch (\Exception $e) {
            return new Result(false,"Failed to delete User.",[],400);
        }

        return new Result(true,"User successfully deleted.",[],200);
    }
}