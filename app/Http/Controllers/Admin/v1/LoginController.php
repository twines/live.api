<?php

namespace App\Http\Controllers\Admin\v1;

use App\Admin;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    //
    /**
     * Create a new AuthController instance.
     * 要求附带email和password（数据来源users表）
     *
     * @return void
     */
    public function __construct()
    {
        // 这里额外注意了：官方文档样例中只除外了『login』
        // 这样的结果是，token 只能在有效期以内进行刷新，过期无法刷新
        // 如果把 refresh 也放进去，token 即使过期但仍在刷新期以内也可刷新
        // 不过刷新一次作废
        $this->middleware('auth:api', ['except' => ['login', 'auth']]);
        // 另外关于上面的中间件，官方文档写的是『auth:api』
        // 但是我推荐用 『jwt.auth』，效果是一样的，但是有更加丰富的报错信息返回
    }

    public function login(Request $request)
    {
        $userName = $request->get('userName');
        $password = $request->get('password');
        $messages = [
            'userName.required' => '用户名不可以为空',
            'password.required' => '密码不可以为空',
        ];
        $rules = [
            'userName' => 'required',
            'password' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $msg = '';
            foreach ($validator->errors()->messages() as $v) {
                $msg .= $v[0];
            }
            return $this->error($msg);
        }


        $admin = Admin::where('name', $userName)->first();
        if (!$admin) {
            return $this->error('用户不存在');
        }
        //先不校验密码
        if (md5($password) != $admin->password) {
            return $this->error('密码错误');
        }
        if (!$token = auth('api')->login($admin)) {
            return $this->error('认证失败');
        }

        return $this->respondWithToken($token, $admin->toArray());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('admin')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     * 刷新token，如果开启黑名单，以前的token便会失效。
     * 值得注意的是用上面的getToken再获取一次Token并不算做刷新，两次获得的Token是并行的，即两个都可用。
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh(), null);
    }

    /**
     * @param $token
     * @param $user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user)
    {
        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user,
        ]);
    }
}
