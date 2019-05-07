<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Transformers\Api\UserTransformer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\Api\OAuthLoginRequest;

class OAuthController extends BaseController
{
    /*
     * 微信登录
     */
    public function login(OAuthLoginRequest $request)
    {
        $code = 'wxcode_'.$request->get('code');
        if (Redis::exists($code)) {
            $session_key = Redis::get($code);
        } else {
            $data = $this->app->auth->session($request->get('code'));
            if (isset($data['errcode']) && $data['errcode'] == 40029) {
                $this->response->error('Code 不正确!', 201);
            } elseif (isset($data['errcode']) && $data['errcode'] == 40163) {
                $this->response->error('Code已经被使用啦!', 201);
            } else {
                $session_key = $data['session_key'];
                Redis::setex($code, 2592000, $session_key);
            }
        }
        $iv = $request->get('iv');
        $encryptedData = $request->get('encryptedData');
        $decryptedData = $this->app->encryptor->decryptData($session_key, $iv, $encryptedData);
        if (isset($decryptedData['openId'])) {
            $openId = $decryptedData['openId'];
            $name = $decryptedData['nickName'];
            $model = User::where('weixin_openid', $openId);
            if ($model->exists()) {
                $user = $model->first();
            } else {
                $user = User::create([
                    'name' => $name,
                    'password' => $openId,
                    'weixin_openid' => $openId,
                ]);
            }

            return $this->response->item($user, new UserTransformer())
                ->setMeta([
                    'access_token' => Auth::guard('api')->fromUser($user),
                    'token_type' => 'Bearer',
                    'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
                ])
                ->setStatusCode(201);
        }
    }
}
