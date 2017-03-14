<?php

namespace App\Http\Middleware;

use Closure;

class ProfileChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $profile)
    {
        if(\Auth::user()->users_role != $profile){
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                \Auth::logout();
                return redirect('/');
            }
        }
        return $next($request);
    }
}
