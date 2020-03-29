<?php
/**
 * Created by 莘莘
 * User: 寒云
 * Email: 1355081829@qq.com
 * Date: 2020/3/27
 * Time: 22:05
 */

namespace App\Repository\Admin\v1;


use App\Models\Permission;

class PermissionRepository
{
    public function getPermissionList()
    {
        return Permission::all();
    }

    public function getRolePermission($roleId)
    {
        return Permission::all();
    }
}
