<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\TeacherJWTToken;
use Illuminate\Support\Facades\Cookie;

class TeacherTokenExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token_teacher=Cookie::get('token_teacher');
        $result=TeacherJWTToken::ReadToken($token_teacher);
         if($result=="unauthorized"){
             return $next($request);
         }else{
             return redirect('/admin/dashboard');
         }
    }
}
