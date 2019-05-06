<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    //'middleware' => ['serializer:array', 'bindings', 'change-locale'],
], function ($api) {

    //登录注册刷新Token模块
    $api->group([
        'middleware' => 'api.throttle',
        'prefix' => 'auth',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function ($api) {
        $api->post('login', 'AuthController@login');
        $api->post('register', 'AuthController@register');
        $api->post('logout', 'AuthController@logout');
        $api->post('refresh', 'AuthController@refresh');
    });

    $api->group([
        'middleware' => 'api.throttle',
        'prefix' => 'oauth',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function ($api) {
        //普通登录
        $api->post('wechat', 'OAuthController@login');
    });

    //需要登录才可以查看的信息
    $api->group([
        'middleware' => 'api.throttle|jwt.auth',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        // 用户信息查看
        $api->get('user', 'UsersController@show')->name('api.users.show');
        //用户登录之后,必须选择省份才可以查询
        $api->post('user/province', 'UsersController@province')->name('api.users.province');
    });
});
