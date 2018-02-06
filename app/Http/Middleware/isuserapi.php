<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class isuserapi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request,Closure $next)
    {
      if (Auth::user()) {
        return $next($request);
      }else {
        return response()->json(["code"=>403 ,'message'=>"You don't have permission to access"], 403);
      }
    }
}
