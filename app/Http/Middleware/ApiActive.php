<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\ApiTrait;

class ApiActive
{
    use ApiTrait;
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->user()->status == 'pending'){
            $msg = trans('auth.pending_user');
            return $this->failMsg($msg);
        }elseif($request->user()->status == 'block'){
            $msg = trans('auth.blocked_user');
            return $this->failMsg($msg);
        }
        return $next($request);
    }
}
