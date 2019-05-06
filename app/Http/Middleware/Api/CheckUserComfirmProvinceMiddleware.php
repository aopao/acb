<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserComfirmProvinceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string|null              $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $user = Auth::guard('api')->user();
        if ($user->province_id == 0) {
            return response()->json(['message' => '请填写考生信息', 'status_code' => 301]);
        }

        return $next($request);
    }
}
