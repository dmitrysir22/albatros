<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CandidateAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Если пользователь залогинен — пропускаем дальше
        if (Auth::check()) {
            return $next($request);
        }

        // Если нет — кидаем на твой любимый userlogin
        return redirect()->route('userlogin');
    }
}