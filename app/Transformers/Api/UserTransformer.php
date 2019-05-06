<?php

namespace App\Transformers\Api;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'Id' => $user->id,
            'Name' => $user->name,
            'Mobile' => $user->mobile,
            'ProvinceId' => $user->province_id,
            'Number' => $user->number,
            'WeixinOpenid' => $user->weixin_openid,
            'WeixinUnionid' => $user->weixin_unionid,
            'CreatedAt' => $user->created_at->toDateTimeString(),
            'UpdatedAt' => $user->updated_at->toDateTimeString(),
        ];
    }
}
