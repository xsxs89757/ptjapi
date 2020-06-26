<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends AdminApiController
{
    
    public function show(Request $request)
    {
    	return $this->success(['test']);
    }
}
