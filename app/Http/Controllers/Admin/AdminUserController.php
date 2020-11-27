<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;
use App\Http\Resources\Admin\AdminUser  as AdminUserResources;  //登录详细资料
use App\Http\Resources\Admin\AdminUsers as AdminUsersResources; //登录列表
use App\Http\Resources\Admin\AdminActionLog as AdminActionLogResources; //日志
use App\Models\AdminUsers;
use App\Models\AdminMenu;
use App\Models\Roles;
use App\Models\AdminActionLog;
use Spatie\Permission\Models\Permission;

class AdminUserController extends AdminApiController
{
	/**
	 * 获取详细资料
	 * @param  $request
	 * @return json
	 */
	protected function info(Request $request)
	{
		$user = Auth::user();
		$loginInfo = new AdminUserResources($user);
		return $loginInfo;	
	}

	/**
	 * 后台用户列表
	 */
	protected function list(Request $request)
	{
		$userid = Auth::id();
		$limit = $request->input('limit',20);
		$id = $request->input('id');
		$username = $request->input('username');
		$sortName = $request->input('sortName');
		$sort = $request->input('sort');
		$list = AdminUsers::with(['adminUsers'])
		->when($userid !==1 ,function($query) use($userid){
			$query->where('create_uid',$userid);
		})->when(!empty($id),function($query) use($id){
			$query->where('id',$id);
		})->when(!empty($username),function($query) use($username){
			$query->where('username',$username);
		})->when($sort && $sortName,function($query) use($sort,$sortName){
			$query->orderBy($sortName,$sort);
		})->paginate($limit);
		$list = AdminUsersResources::collection($list);
		return $list;
	}


	/**
	 *  添加后台用户
	 */
	protected function add(Request $request)
	{
		$rules = [
			'username' => 'required',
			'password' => 'required|min:6|confirmed',
			'password_confirmation' => 'required|min:6',
			'roles'=> 'required|array'
		];
		$this->validate($request, $rules);
		$attributes = [
			'username'=>$request->input('username'),
			'nickname'=>$request->input('nickname'),
			'facephoto'=>$request->input('facephoto')?$request->input('facephoto'):'facephoto/default.gif',
			'password'=>bcrypt($request->input('password')),
			'status'=>$request->input('status')?1:0,
			'create_uid'=>Auth::id(),
			'create_time'=>time(),
			'last_login_time'=>time(),
			'last_login_ip'=>$request->getClientIp()
		];
		$roles = $request->input('roles');
		$adminUsers = AdminUsers::create($attributes,$roles);
		if($adminUsers){
			$detail = AdminUsers::with(['adminUsers'])->find($adminUsers->id);
			$detail = new AdminUsersResources($detail);
			return $detail;
			//return $this->create($detail->resc->toArray());
		}else{
			throw new \App\Exceptions\ApiException(50000);
		}
	}
	

	/**
	 * 编辑后台用户 
	 */
	protected function edit(Request $request)
	{
		$rules = [
			'id' => 'required|numeric',
			'username' => 'required',
			'password' => 'nullable|min:6|confirmed',
			'password_confirmation' => 'nullable|min:6',
			'roles'=> 'required|array'
		];
		$this->validate($request, $rules);
		$attributes = [
			'id'=>$request->input('id'),
			'username'=>$request->input('username'),
			'nickname'=>$request->input('nickname'),
			'facephoto'=>$request->input('facephoto')?$request->input('facephoto'):'facephoto/default.gif',
			'status'=>$request->input('status')?1:0
		];
		$password = $request->input('password');
		if(!empty($password)){
		   $attributes['password'] = bcrypt($password);
		}
		$roles = $request->input('roles');
		$adminUsers = AdminUsers::saveById($attributes,$roles);
		if($adminUsers){
			return $this->accepted();
		}else{
			throw new \App\Exceptions\ApiException(50001);
		}
	}

	/**
	 * 删除用户
	 */
	protected function delete($id)
	{
		if($id == 1){
			//无法删除超管
			return $this->failed(40303);        }
		$uid = Auth::id();
		if($uid === 1){ //超管
			AdminUsers::destroy($id);
		}else{
			$users = AdminUsers::find($id);
			if($users->create_uid === $uid){
				AdminUsers::destroy($id);
			}else{
				return $this->failed(40302);          
			}
		}
		return $this->deleteOrPutPatch();
	}


	/**
	 * 用户操作日志
	 */
	
	protected function logs(Request $request)
	{
		$userid = Auth::id();
		$limit = $request->input('limit',20);
		$uid = $request->input('uid');
		$sortName = $request->input('sortName');
		$sort = $request->input('sort');
		$usersIn = [];
		if($userid !==1){
			$usersIn = AdminUsers::where('create_uid',$uid)->pluck('id')->toArray();
			array_unshift($usersIn,$userid);
		}
		$list = AdminActionLog::with(['adminUsers'])
		->when($userid !==1 ,function($query) use($usersIn){
			$query->whereIn('action_uid',$usersIn);
		})->when(!empty($uid),function($query) use($uid){
			$query->where('action_uid',$uid);
		})->when($sort && $sortName,function($query) use($sort,$sortName){
			$query->orderBy($sortName,$sort);
		})->paginate($limit);
		$list = AdminActionLogResources::collection($list);
		return $list;
	}

	/**
	 * 清空日志
	 */
	protected function clearLogs()
	{
		$userid = Auth::id();
		if($userid===1){
			AdminActionLog::truncate();
			return $this->deleteOrPutPatch();	
		}else{
			return $this->failed(40301);
		}
		
	}
	
	/**
	 * 修改密码
	 * @Author   lei.wang
	 * @DateTime 2019-06-14T16:17:08+0800
	 * @param    Request                  $request
	 * @return   Json
	 */
	protected function resetPassword(Request $request)
	{
		$rules = [
			'oldPassword' => 'required|min:6',
			'password' => 'required|min:6|confirmed'
		];
		$this->validate($request, $rules);
		$input = $request->input();
		$user = Auth::user();
		if (\Hash::check($request->get('oldPassword'),$user->password)){
			$user->password = bcrypt($request->input('password'));
			$user->save();
			return $this->accepted();
		}else{
			throw new \App\Exceptions\ApiException(50001);
		}
	}
	/**
	 * 开发模式
	 * @Author   lei.wang
	 * @DateTime 2019-06-14T16:17:50+0800
	 * @param    Request                  $request
	 * @return   Json                     
	 */
	protected function dev(Request $request)
	{
		$dev = $request->input('dev');
		if($dev === "1"){
			AdminMenu::whereIn('id',[4,9])->update(['hidden'=>0]); //隐藏菜单
			Permission::whereIn('name',['system.list','system.list.add','system.list.edit','system.list.delete','system.list.sort',
			'menu','menu.addMenu','menu.editMenu','menu.deleteMenu','menu.sortMenu'])
			->update(['guard_name'=>'admin']); //放入闲置门面
		}else{
			AdminMenu::whereIn('id',[4,9])->update(['hidden'=>1]); //隐藏菜单
			Permission::whereIn('name',['system.list','system.list.add','system.list.edit','system.list.delete','system.list.sort',
			'menu','menu.addMenu','menu.editMenu','menu.deleteMenu','menu.sortMenu'])
			->update(['guard_name'=>'leave']); //放入闲置门面
		}
		AdminMenu::refreshCachePermissionRoleMenu(); //更新权限
		return $this->accepted();
	}

}
