<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SessionSemester
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if ($request->user()->hasRole($role, session('semester_id'))) {
            return $next($request);
        }
        return abort(403);
    }
}
