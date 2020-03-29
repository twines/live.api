<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repository\Api\v1\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    //
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');
        if (!$username) {
            return $this->error('用户名不可以为空');
        }
        if (!$password) {
            return $this->error('密码不可以为空');
        }
        $user = $this->userRepository->getUserByName($username);
        if ($user) {
            return $this->error('用户已经存在');
        }
        $data = [];
        $data['password'] = Hash::make($password);
        $data['username'] = $username;
        if ($this->userRepository->addUser($data)) {
            return $this->success();
        }
        return $this->error();
    }
}
