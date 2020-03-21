<?php


namespace App\Components\User;


use App\Components\Core\BaseManager;
use App\Components\Core\Result;
use App\Components\Core\Utilities\Helpers;
use App\Components\User\Models\Group;
use App\Components\User\Repositories\GroupRepository;
use App\Components\User\Repositories\PermissionRepository;
use Illuminate\Http\Request;

class GroupManager extends BaseManager
{
    /**
     * @var \App\Components\User\Repositories\GroupRepository
     */
    private $groupRepository;
    /**
     * @var \App\Components\User\Repositories\PermissionRepository
     */
    private $permissionRepository;

    /**
     * GroupManager constructor.
     *
     * @param \App\Components\User\Repositories\GroupRepository $groupRepository
     *
     * @param \App\Components\User\Repositories\PermissionRepository $permissionRepository
     *
     * @since  v1.0
     * @author darryldecode <darrylfernandez.com>
     */
    public function __construct(GroupRepository $groupRepository, PermissionRepository $permissionRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Components\Core\Result
     */
    public function listGroups(Request $request)
    {
        $data = $this->groupRepository->index($request->all());

        return new Result(true,"List groups ok.",$data,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \App\Components\Core\Result
     */
    public function store(Request $request)
    {
        $validate = validator($request->all(),[
            'name' => 'required',
            'permissions' => 'string',
        ]);

        if($validate->fails()) return new Result(false,$validate->errors()->first(),[],400);

        $permissionsArray = [];

        if($request->has('permissions') && !empty($request->get('permissions')))
        {
            $permissions = Helpers::dismantleToAssociativeArray($request->get('permissions'));

            foreach ($permissions as $key => $value)
            {
                if($Permission = $this->permissionRepository->getByKey($key))
                {
                    $PData = $Permission->toArray();
                    $PData['value'] = $value;
                    $permissionsArray[] = $PData;
                }
            }
        }

        $group = $this->groupRepository->create([
            'name' => $request->get('name'),
            'permissions' => $permissionsArray,
        ]);

        return new Result(true,"New group created.",$group,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \App\Components\Core\Result
     */
    public function showById($id)
    {
        $group = $this->groupRepository->find($id);

        if(!$group) return new Result(false, "Group not found.",[],404);

        return new Result(true,"Group found.",$group,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \App\Components\Core\Result
     */
    public function update(Request $request, $id)
    {
        $validate = validator($request->all(),[
            'name' => 'required',
            'permissions' => 'string',
        ]);

        if($validate->fails()) return new Result(false,$validate->errors()->first(),[],400);

        $permissionsArray = [];

        if($request->has('permissions') && !empty($request->get('permissions')))
        {
            $permissions = Helpers::dismantleToAssociativeArray($request->get('permissions'));

            foreach ($permissions as $key => $value)
            {
                if($Permission = $this->permissionRepository->getByKey($key))
                {
                    $PData = $Permission->toArray();
                    $PData['value'] = $value;
                    $permissionsArray[] = $PData;
                }
            }
        }

        $updated = $this->groupRepository->update($id,[
            'name' => $request->get('name'),
            'permissions' => $permissionsArray,
        ]);

        if(!$updated) return new Result(false,"Failed to update group.",[],400);

        return new Result(true,"Group successfully updated.",[],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \App\Components\Core\Result
     */
    public function deleteGroup($id)
    {
        /** @var Group $group */
        $group = $this->groupRepository->find($id);

        if(!$group) return new Result(false,"Group not found.",[],404);

        // prevent delete of super user
        if($group->name == Group::SUPER_USER_GROUP_NAME) return new Result(false,"Forbidden action.",[],401);

        // detach all users first
        $group->users()->detach();

        try {
            $this->groupRepository->delete($id);
        } catch (\Exception $e) {
            return new Result(false,"Failed to delete group.",[],400);
        }

        return new Result(true,"Group deleted successfully.",[],200);
    }
}