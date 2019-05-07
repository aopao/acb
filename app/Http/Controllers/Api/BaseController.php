<?php

namespace App\Http\Controllers\Api;

use EasyWeChat\Factory;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseController extends Controller
{
    use Helpers;

    protected $app;

    public function __construct()
    {
        $this->app = Factory::miniProgram(config('wechat.mini_program.default'));
    }

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
