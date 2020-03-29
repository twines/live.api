<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Repository\Admin\v1\PermissionRepository;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //
    private $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function getPermissionList(Request $request)
    {
        $allPermissionList = $this->permissionRepository->getPermissionList();
        $permissionList = $this->permissionRepository->getRolePermission(8);
        $data['allPermissions'] = $allPermissionList->toArray();
        $data['permissionSlice'] = $permissionList->toArray();
        return $this->success($data);
    }
}
