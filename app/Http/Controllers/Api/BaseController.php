<?php

namespace App\Http\Controllers\Api;

use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseController extends Controller
{
    //Dingo 助手
    use Helpers;

    /**
     * 错误响应吗
     *
     * @param      $statusCode
     * @param null $message
     * @param int  $code
     */
    public function errorResponse($statusCode, $message = null, $code = 0)
    {
        throw new HttpException($statusCode, $message, null, [], $code);
    }
}
