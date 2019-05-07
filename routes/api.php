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

    //登录注册刷新ToKen模块
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
        //微信登录
        $api->post('login', 'OAuthController@login');
    });

    //游客可查看路由
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        //大学列表
        $api->get('colleges', 'CollegeController@index')->name('api.college.index');
        //大学标签属性
        $api->get('college/attribute', 'CollegeController@attribute')->name('api.college.attribute');

    });

    //需要登录才可以查看的信息
    $api->group([
        'middleware' => 'api.throttle|jwt.auth',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        // 用户信息查看
        $api->get('user', 'UsersController@show')->name('api.users.show');

        //大学详细信息
        $api->get('college/{id}', 'CollegeController@show')->name('api.college.show');
        $api->get('college/{id}/articles', 'CollegeController@articles')->name('api.college.articles');
        $api->get('article/{id}', 'CollegeController@getArticles')->name('api.college.getArticles');
        $api->get('college/{id}/scores', 'CollegeController@scores')->name('api.college.score');

        //用户登录之后,必须选择省份才可以查询
        $api->post('user/province', 'UsersController@province')->name('api.users.province');
    });
});
