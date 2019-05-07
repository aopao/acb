<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Api\UserTransformer;

class AuthController extends BaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        // 但是我推荐用 『jwt.auth』，效果是一样的，但是有更加丰富的报错信息返回
    }

    /**
     * 注册方法
     *
     * @param \App\Http\Requests\UserRequest $request
     */
    public function register(UserRequest $request)
    {
        $user = User::create([
            'role_id' => 2,
            'mobile' => $request->get('mobile'),
            'password' => $request->get('password'),
        ]);

        $this->response->item($user, new UserTransformer())
            ->setMeta([
                'access_token' => Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            ])
            ->setStatusCode(201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param \App\Http\Requests\UserRequest $request
     * @throws \ErrorException
     */
    public function login(UserRequest $request)
    {
        $credentials['mobile'] = $request->get('mobile');
        $credentials['password'] = $request->get('password');

        if (! $token = auth('api')->attempt($credentials)) {
            $this->response->errorUnauthorized("用户名或者密码错误!");
        }

        $this->respondWithToken($token)->setStatusCode(201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return mixed
     * @throws \ErrorException
     */
    public function logout()
    {
        JWTAuth::parseToken()->invalidate();

        return $this->response->array(['message' => '退出成功!']);
    }

    /**
     * Refresh a token.
     *
     * @return mixed
     * @throws \ErrorException
     */
    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::parseToken()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param $token
     * @return mixed
     * @throws \ErrorException
     */
    protected function respondWithToken($token)
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ];

        return $this->response->array($data);
    }
}
