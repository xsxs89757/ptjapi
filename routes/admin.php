<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', 'IndexController@show')->name('show'); //test


Route::middleware('admin.action.log')->post('/login', 'AuthController@login')->name('login');
Route::post('/admin-user/logout', 'AuthController@logout')->name('logout');

Route::middleware(['refresh.admin.token','admin.action.log'])->group(function($router) {
	/*登录后公共拥有权限部分*/
    $router->get('/admin-user/info','AdminUserController@info')->name('userInfo');
    $router->get('/routers','RouterController@list')->name('routeList');
    $router->get('/menu/channel','MenuController@getChannel')->name('menu.channel');
    $router->put('/admin-user/reset-password','AdminUserController@resetPassword')->name('resetPassword');
    /*公共上传部分*/

    $router->post('/upload/signle-image','UploadController@signleImage')->name('upload.signleImage');
    $router->post('/upload/up-config','UploadController@upConfig')->name('upload.upConfig');


    $router->get('/admin-user/dev','AdminUserController@dev')->name('dev');

    //筛选
    $router->get('/roles-list','RolesController@rolesList')->name('adminUsers.role');

    Route::middleware('auth.permission')->group(function($router) {
        /**
         * 下面的根据name路由来区分是否有权限
         */
        
    	/*role管理*/
    	$router->get('/roles','RolesController@list')->name('adminUsers.role');
    	$router->post('/roles','RolesController@add')->name('adminUsers.role.addRole');
    	$router->delete('/roles/{id}','RolesController@delete')->name('adminUsers.role.deleteRole')->where('id', '[0-9]+');
    	$router->put('/roles','RolesController@edit')->name('adminUsers.role.editRole');
    	
        /*adminUsers管理*/
        $router->get('/admin-users','AdminUserController@list')->name('adminUsers.list');
        $router->put('/admin-users','AdminUserController@edit')->name('adminUsers.list.editAdminUser');
        $router->delete('/admin-users/{id}','AdminUserController@delete')->name('adminUsers.list.deleteAdminUser')->where('id', '[0-9]+');
        $router->post('/admin-users','AdminUserController@add')->name('adminUsers.list.addAdminUser');
        //日志
        $router->get('/admin-logs','AdminUserController@logs')->name('adminControllerLogs');
        $router->delete('/clear-admin-logs','AdminUserController@clearLogs')->name('adminControllerLogs.clearAdminLogs');
        //menu
        $router->get('/menu','MenuController@list')->name('menu');
        $router->post('/menu','MenuController@add')->name('menu.addMenu');
        $router->put('/menu','MenuController@edit')->name('menu.editMenu');
        $router->delete('/menu/{id}','MenuController@delete')->name('menu.deleteMenu')->where('id', '[0-9]+');
        $router->put('/menu/sort','MenuController@sort')->name('menu.sortMenu');

        //config
        $router->get('/system-maps-options','SystemController@mapsOptions')->name('system.list');
        $router->get('/system-maps-group','SystemController@mapsGroup')->name('system.config');
        $router->get('/system-group','SystemController@group')->name('system.config');
        $router->get('/system-config','SystemController@config')->name('system.config');
        $router->put('/system-batch','SystemController@batch')->name('system.config.save');
        $router->get('/system','SystemController@list')->name('system.list');
        $router->post('/system','SystemController@add')->name('system.list.add');
        $router->put('/system','SystemController@edit')->name('system.list.edit');
        $router->delete('/system/{id}','SystemController@delete')->name('system.list.delete')->where('id', '[0-9]+');
        $router->put('/system/sort','SystemController@sort')->name('system.list.sort');

        //dictionary 字典
        $router->get('/dictionary','DictionaryController@list')->name('system.dictionary');
        $router->get('/dictionary/{name}','DictionaryController@detail')->name('system.dictionary');
        $router->delete('/dictionary/{id}','DictionaryController@delete')->name('system.dictionary.delete')->where('id', '[0-9]+');
        $router->post('/dictionary','DictionaryController@add')->name('system.dictionary.add');
        $router->put('/dictionary','DictionaryController@edit')->name('system.dictionary.edit');
        $router->put('/dictionary/save','DictionaryController@save')->name('system.dictionary.save');

	});

});