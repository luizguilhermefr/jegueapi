<?php

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedUserException;
use App\User;
use Closure;
use Illuminate\Http\Request;

class LoggedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure $next
     * @return mixed
     * @throws UnauthorizedUserException
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('X-token');
        if (empty($token) || ! $request->user = User::findByToken($token)) {
            throw new UnauthorizedUserException();
        }

        return $next($request);
    }
}
