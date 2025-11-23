<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureProfileIsComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $missing = [];

        if (empty($user->username)) $missing[] = 'username';
        if (empty($user->address))  $missing[] = 'address';
        if (empty($user->number))   $missing[] = 'number';

        if (count($missing) > 0) {
            return response()->json([
                'message' => 'You must complete your profile',
                'missing_fields' => $missing
            ], 403);
        }

        return $next($request);
    }

}
