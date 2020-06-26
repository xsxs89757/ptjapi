<?php

use Illuminate\Database\Seeder;

use App\Models\AdminMenu; //判断是否为空  为空填充初始化数据  不然不填充

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		$count = AdminMenu::count();
		if($count==0){
			//无数据的情况下 进行初始数据填充
			$this->call(AdminUsersTableSeeder::class); //会员
			$this->call(AdminMenuSeeder::class); //菜单
			$this->call(RolesAndPermissionsSeeder::class); //权限
		}

	}
}
