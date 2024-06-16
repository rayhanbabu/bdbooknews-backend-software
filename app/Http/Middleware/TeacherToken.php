<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\TeacherJWTToken;
use Illuminate\Support\Facades\Cookie;

class TeacherToken
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
              return redirect('/admin/login');
         }else { 
              $request->headers->set('email',$result->email);
              $request->headers->set('dept_id',$result->dept_id);
              $request->headers->set('role',$result->role);
              $request->headers->set('id',$result->id);
              $request->headers->set('teacher_name',$result->teacher_name);
              return $next($request);
        }
    }
}
