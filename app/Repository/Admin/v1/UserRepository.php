<?php
/**
 * Created by 莘莘
 * User: 寒云
 * Email: 1355081829@qq.com
 * Date: 2020/3/29
 * Time: 14:11
 */

namespace App\Repository\Admin\v1;


use App\Models\UserProfile;
use App\User;

class UserRepository
{
    public function getUserList($keyword = null, $status = null)
    {
        return User::orderby('id', 'desc')
            ->where(function ($q) use ($keyword) {
                if ($keyword) {
                    $q->orWhere('mobile', $keyword)
                        ->orWhere('username', 'like', "%$keyword%")
                        ->orWhere('nickname', 'like', "%$keyword%");
                }
            })
            ->where(function ($q) use ($status) {
                if (!is_null($status)) {
                    $q->where('status', $status);
                }
            })
            ->paginate();
    }

    public function getUserByUsername($username)
    {
        return User::where('username', $username)->first();
    }

    public function getUserById($userId)
    {
        return User::find($userId);
    }

    public function addUser($data)
    {
        return User::create($data);
    }

    public function deleteUser($userId)
    {
        return User::where('id', $userId)->delete();
    }

    public function updateUser($userId, $data)
    {
        return User::where('id', $userId)->update($data);
    }

    public function getAuthList($keyword = null, $status = null)
    {
        return UserProfile::leftjoin('users', 'users.id', '=', 'user_profiles.user_id')
            ->where(function ($q) use ($keyword) {
                if ($keyword) {
                    $q->orWhere('users.mobile', $keyword)
                        ->orWhere('users.username', 'like', "%$keyword%")
                        ->orWhere('user_profiles.true_name', 'like', "%$keyword%")
                        ->orWhere('users.nickname', 'like', "%$keyword%");
                }
            })
            ->where(function ($q) use ($status) {
                if (!is_null($status)) {
                    $q->where('users.status', $status);
                }
            })
            ->select('user_profiles.*', 'users.username', 'users.qq', 'users.email', 'users.mobile')
            ->orderby('user_profiles.id', 'desc')
            ->paginate();
    }
}
