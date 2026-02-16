<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Проверяем: залогинен ли юзер И есть ли у него роль admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Если не админ — выкидываем на главную с ошибкой
        return redirect('/')->with('error', 'You do not have administrative access.');
    }
}