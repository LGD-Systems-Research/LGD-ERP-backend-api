<?php


namespace App\Components\User;


use App\Components\Core\BaseManager;
use App\Components\Core\Result;
use App\Components\User\Models\Permission;
use App\Components\User\Repositories\PermissionRepository;
use Illuminate\Http\Request;

class PermissionManager extends BaseManager
{
    /**
     * @var \App\Components\User\Repositories\PermissionRepository
     */
    private $permissionRepository;

    /**
     * PermissionManager constructor.
     *
     * @param \App\Components\User\Repositories\PermissionRepository $permissionRepository
     *
     * @since  v1.0
     * @author darryldecode <darrylfernandez.com>
     */
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Components\Core\Result
     */
    public function index(Request $request)
    {
        $data = $this->permissionRepository->index($request->all());

        return new Result(true,"Get all permissions ok.",$data,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Components\Core\Result
     */
    public function createPermission(Request $request)
    {
        $validate = validator($request->all(),[
            'title' => 'required|string',
            'description' => 'required|string',
            'key' => 'required|string|unique:permissions',
        ]);

        if($validate->fails()) return new Result(false,$validate->errors()->first(),[],400);

        $permission = $this->permissionRepository->create($request->all());

        if(!$permission) return new Result(false,"Failed to create permission.", [], 400);

        return new Result(true,"New permission created successfully.",$permission,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \App\Components\Core\Result
     */
    public function show($id)
    {
        $permission = $this->permissionRepository->find($id,['permissions']);

        if(!$permission) return new Result(false,"Permission not found.",[],404);

        return new Result(true,"Permission found.",$permission,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \App\Components\Core\Result
     */
    public function update(Request $request, $id)
    {
        $validate = validator($request->all(),[
            'title' => 'required|string',
            'description' => 'required|string',
            'key' => 'required|string',
        ]);

        if($validate->fails()) return new Result(false,$validate->errors()->first(),[],400);

        $updated = $this->permissionRepository->update($id,$request->all());

        if(!$updated) return new Result(false,"Failed to update Permission.",[],400);

        return new Result(true,"Permission successfully updated.",[],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \App\Components\Core\Result
     */
    public function deletePermission($id)
    {
        /** @var Permission $permission */
        $permission = $this->permissionRepository->find($id);

        if(!$permission) return new Result(false,"Permission not found.",[],404);

        // prevent delete of super user permission
        if($permission->key == Permission::SUPER_USER_PERMISSION_KEY)
        {
            return new Result(false,"Forbidden action.",[],401);
        }

        try {
            $this->permissionRepository->delete($id);
        } catch (\Exception $e) {
            return new Result(false, "Failed to Delete, an exception has been thrown and added to our logs.",[],400);
        }

        return new Result(true,"permission successfully deleted.",[],200);
    }
}