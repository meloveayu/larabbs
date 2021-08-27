<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 三个判断
        // 1. 若用户已登录
        // 2. 且未认证邮箱
        // 3。 且访问的不是邮箱验证相关url或者退出的url
        if ($request->user() &&
            ! $request->user()->hasVerifiedEmail() &&
            ! $request->is('email/*','logout'))
        {
            return $request->expectsJson()
                ? abort(403,'Your email address is not verified.')
                : redirect()->route('verification.notice');
        }
        return $next($request);
    }
}
