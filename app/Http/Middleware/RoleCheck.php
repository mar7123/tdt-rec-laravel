<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $rl = $request->user()->role()->first();
        if ($rl->name != $role) {
            return Response([
                'message' => 'Unauthorized',
            ], 401);
        }
        return $next($request);
    }
}
