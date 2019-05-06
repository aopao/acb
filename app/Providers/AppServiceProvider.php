<?php

namespace App\Providers;

use API;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        API::error(function (\Illuminate\Auth\Access\AuthorizationException $exception) {
            abort(403, $exception->getMessage());
        });

        API::error(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception) {
            throw  new  \Symfony\Component\HttpKernel\Exception\HttpException(404, '未找到相关数据!');
        });

        API::error(function (\Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException $exception) {
            throw  new  \Symfony\Component\HttpKernel\Exception\HttpException(403, '禁止访问!');
        });

        API::error(function (\Tymon\JWTAuth\Exceptions\TokenExpiredException $exception) {
                throw  new  \Symfony\Component\HttpKernel\Exception\HttpException(201, '登录失效!');
        });
    }
}
