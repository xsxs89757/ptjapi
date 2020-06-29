<?php

namespace App\Exceptions;

use Exception;
use App\Helpers\ApiResponse;

class ApiException extends Exception
{
	use ApiResponse;
	/**
	 * 将异常渲染至 HTTP 响应值中。
	 *
	 * @param  \Illuminate\Http\Request
	 * @return \Illuminate\Http\Response
	 */
	public function render($request)
	{
		return $this->failed($this->getMessage());
	}
}
