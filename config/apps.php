<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Application URL
	|--------------------------------------------------------------------------
	|
	| The root URL of each application.
	|
	*/

	'url' => [
		'web' => env('APP_URL'),
		'admin' => env('APP_URL_ADMIN', env('APP_URL')), //后端api地址
		//'api' => env('APP_URL_API', env('APP_URL')), //前端类url
		'assets' => env('APP_URL_ASSETS', env('APP_URL')), //附件地址
	],

	/*
	|--------------------------------------------------------------------------
	| Service Providers
	|--------------------------------------------------------------------------
	|
	| The service providers listed here will be automatically registered for
	| each application.
	|
	*/

	'providers' => [

		'admin' => [
			//独立注入
		],

		'api' => [
			//
		],

	],

	/*
	|--------------------------------------------------------------------------
	| Application Configuration
	|--------------------------------------------------------------------------
	|
	| Here you may override the default configurations for each application.
	|
	*/

	'config' => [

		'default' => [
			'app.editor' => env('APP_EDITOR'),
		],

		'admin' => [
			'auth.guard' => [
				 'admin' => [
					 'driver' => 'jwt',
					 'provider' => 'adminUsers'
				 ]
			],
			'auth.defaults.guard' => 'admin',
			'filesystems.default' => 'public',
			'filesystems.disks.public.url' => env('APP_URL_ASSETS', env('APP_URL')).'/storage',
			'session.domain' => env('SESSION_DOMAIN_ADMIN', null),
			'auth.providers' => [
				'admin' => [
					'driver' => 'eloquent',
					'model' => App\Models\AdminUsers::class
				]
			]

		],

		'api' => [
			'auth.defaults.guard' => 'api',
		],

	],

];
