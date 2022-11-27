<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class CheckLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::check()){
            return redirect('/login');
        }
        $user_id = Auth::id();
        $user = User::find($user_id);
        //获取当前用户对应的角色
        $roles = $user->roles;
        //根据用户的角色，查找对应的权限
        $urls = ['home','welcome'];
        foreach($roles as $role){
            $permissions = $role->permissions;
            foreach($permissions as $permission){
                $urls[] = $permission->url;
            }
        }
        $urls = array_unique(array_filter($urls));
        //获取当前路由
        $route = $request->path();
        if(!in_array($route,$urls)){
            dd($route);
        }
        
        //判断
        return $next($request);
    }
}
