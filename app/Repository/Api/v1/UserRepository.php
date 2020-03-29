<?php
/**
 * Created by 莘莘
 * User: 寒云
 * Email: 1355081829@qq.com
 * Date: 2020/3/29
 * Time: 19:57
 */

namespace App\Repository\Api\v1;


use App\User;

class UserRepository
{
    public function getUserByName($username)
    {
        return User::where('username', $username)->first();
    }

    public function addUser($data)
    {
        return User::create($data);
    }
}
