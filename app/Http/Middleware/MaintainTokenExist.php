<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\MaintainJWTToken;
use Illuminate\Support\Facades\Cookie;

class MaintainTokenExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token_maintain=Cookie::get('token_maintain');
        $result=MaintainJWTToken::ReadToken($token_maintain);
        if($result=="unauthorized"){
            return $next($request);
        }else{
            return redirect('/maintain/dashboard');
        }
    }
}
