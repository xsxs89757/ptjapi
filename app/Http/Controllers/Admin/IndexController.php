<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class IndexController extends AdminApiController
{
    
    public function show(Request $request)
    {
    	broadcast(new \App\Events\Square(['aaaa'=>'bbb111111']));
    	return $this->success(['test111']);
    }
}
