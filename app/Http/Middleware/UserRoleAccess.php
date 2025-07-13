<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session; // Tambahkan ini!
use App\Models\User;
use App\Models\Form;

class UserRoleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next): mixed
    {
        $prefix = config('session.prefix');
        $id_user = Session::get("{$prefix}_id_user");
        $role = Session::get("{$prefix}_role");

        if ($id_user) {

            if (in_array($role,[1])) {
                return redirect('/dashboard');
            }
            $user = User::where('id_user', $id_user)->first();
            view()->share('user', $user); // tersedia di semua view
            app()->singleton('user', fn () => $user); // optional

            $form = Form::get();
            view()->share('form', $form); // tersedia di semua view
            app()->singleton('form', fn () => $form); // optional
           
        }

        

        return $next($request);
    }

}
