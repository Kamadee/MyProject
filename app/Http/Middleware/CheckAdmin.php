<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->is_admin) {
                return $next($request);
            }

            return redirect()->route('customer.home'); // Hoặc trang khác nếu không phải admin
        }

        return redirect('/login'); // Hoặc trang đăng nhập nếu chưa đăng nhập
    }
}
