<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_users')->insert([
            'username' => 'admin',
            'password' => bcrypt('123456'),
            //'phone'=>'13793303009',
            'create_time'=>time(),
            'last_login_time'=>time(),
            'last_login_ip'=>'127.0.0.1',
            'introduction'=>'', //预留
            'facephoto'=>'facephoto/default.gif',
            'nickname'=>'超级管理员'
        ]);
    }
}
