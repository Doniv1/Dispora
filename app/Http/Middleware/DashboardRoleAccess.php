<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session; // Tambahkan ini!

class DashboardRoleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $prefix = config('session.prefix');
        $id_user = Session::get("{$prefix}_id_user");
        $role = Session::get("{$prefix}_role");

        if ($id_user) {
            if (!in_array($role,[1])) {
                 return redirect('/home');
            }
           
        }else{
            return redirect('/home');
        }

        return $next($request);
    }
}
