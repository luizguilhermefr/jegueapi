<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Http\Request;

class LoggedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('X-token');
        if (empty($token) || ! $request->user = User::findByToken($token)) {
            return response()->json(['error' => 'UNAUTHORIZED_USER'], 403);
        }

        return $next($request);
    }
}
