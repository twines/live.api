<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Repository\Admin\v1\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserList(Request $request)
    {
        $keyword = $request->get('keyword');
        $status = $request->get('status');
        $userList = $this->userRepository->getUserList($keyword, $status);
        return $this->success($userList->toArray());
    }

    public function addUser(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');
        $user = $this->userRepository->getUserByUsername($username);
        if ($user) {
            return $this->error('用户已经存在');
        }
        $data = [];
        $data['username'] = $username;
        $data['password'] = Hash::make($password);
        if ($this->userRepository->addUser($data)) {
            return $this->success();
        }
        return $this->error();
    }

    public function deleteUser($userId)
    {
        if ($this->userRepository->deleteUser($userId)) {
            return $this->success();
        }
        return $this->error();
    }

    public function getUserDetail($userId)
    {
        $user = $this->userRepository->getUserById($userId);
        if ($user) {
            return $this->success($user->toArray());
        }
        return $this->error();
    }

    public function updateUser(Request $request, $userId)
    {
        $user = $this->userRepository->getUserById($userId);
        if (!$user) {
            return $this->error('用户不存在');
        }
        $data = [];
        $data['password'] = Hash::make($request->get('password'));
        $res = $this->userRepository->updateUser($userId, $data);
        if ($res) {
            return $this->success();
        }
        return $this->error();
    }
}
