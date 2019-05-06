<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Transformers\Api\UserTransformer;

class UsersController extends BaseController
{
    private $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->toUser();
        $this->middleware('check.user.comfirm.province', [
            'except' => ['province'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

        if ($this->user) {
            $user = User::findOrFail($this->user->id);

            return $this->response->item($user, new UserTransformer());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        //
    }

    /**
     * 强制用户填写高考省份
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @throws \ErrorException
     */
    public function province(Request $request)
    {
        if ($this->user->province_id > 0) {
            return $this->response->array(['message' => '您已经绑定啦,已经无法修改啦!', 'status_code' => 201]);
        }
        $res = User::where('id', $this->user->id)->update(['province_id' => $request->get('province_id')]);

        return $res ? $this->response->array(['message' => '绑定成功!', 'status_code' => 200]) : $this->response->array(['message' => '绑定失败!', 'status_code' => 201]);
    }
}
