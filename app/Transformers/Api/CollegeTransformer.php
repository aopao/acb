<?php

namespace App\Transformers\Api;

use App\Models\College;
use League\Fractal\TransformerAbstract;

class CollegeTransformer extends TransformerAbstract
{
    public function transform(College $college)
    {
        return [
            'Id' => $college->id,
            'Name' => $college->name,
            'Mobile' => $college->mobile,
            'ProvinceId' => $college->province_id,
            'Number' => $college->number,
            'WeixinOpenid' => $college->weixin_openid,
            'WeixinUnionid' => $college->weixin_unionid,
            'CreatedAt' => $college->created_at->toDateTimeString(),
            'UpdatedAt' => $college->updated_at->toDateTimeString(),
        ];
    }
}
