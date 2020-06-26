<?php

	namespace App\Helpers;

	class Code
	{	
		/**
		 * @param    code
		 * @return   message
		 */
		public static function getStatusText($code=null){
			if($code){
				return self::statusTexts($code);
			}
		}

		private static function statusTexts($code){
			$statusTexts = [
				40000 => '登录令牌已过期,请重新登录',
				40001 => '异常登录',

				40300 => '用户被禁用',
				40301 => '没有权限清空日志',
				40302 => '没有权限删除当前用户,请使用创建者账户删除',
				40303 => '无法删除超级管理员账户',
				40399 => '没有权限',

				40900 => '用户名或密码不正确',
				40901 => trans('auth.usernameUnique'),
				40902 => trans('auth.menuUnique'),
				40903 => trans('auth.dictionaryUnique'),
				40904 => trans('auth.configUnique'),

				50000 => trans('form.addError'),
				50001 => trans('form.editError')

			];
			if(isset($statusTexts[$code]))return $statusTexts[$code];
		}
	}
	