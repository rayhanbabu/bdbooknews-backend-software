<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\MaintainJWTToken;
use Illuminate\Support\Facades\Cookie;

class MaintainToken
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
              return redirect('/maintain/login');
         }else { 
              $request->headers->set('email',$result->email);
              $request->headers->set('maintain_id',$result->maintain_id);
              $request->headers->set('role',$result->role);
              $request->headers->set('maintain_username',$result->maintain_username);
              $request->headers->set('university_assign',$result->university_assign);
              return $next($request);
        }
    }
}
