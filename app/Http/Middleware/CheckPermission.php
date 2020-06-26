<?php

namespace App\Http\Middleware;

use App\Models\Roles;
use Auth;

use Closure;

class CheckPermission
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		//获取当前路由别名
		$name = \Route::currentRouteName();
		$permissions = Roles::getUsersPermissions();
		//获取是否有当前路由权限
		if(!in_array($name, $permissions) && Auth::id()!==1){
		   throw new \App\Exceptions\ApiException(40001); 
		}
		return $next($request);
		
	}
}
