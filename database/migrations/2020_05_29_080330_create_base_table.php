<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		
		Schema::create('admin_users', function (Blueprint $table) {
			$table->increments('id')->comment('后台用户id');
			$table->string('username')->comment('后台用户名');
			$table->tinyInteger('status')->default(1)->comment('状态');
			$table->string('password',60)->comment('密码');
			$table->string('introduction',150)->nullable()->comment('简介');
			$table->string('facephoto',150)->nullable()->comment('头像');
			$table->string('nickname',50)->comment('真实名称');
			$table->unsignedInteger('create_uid')->default(1)->commit('创建者');
			$table->Integer('create_time')->comment('创建时间');
			$table->Integer('last_login_time')->comment('最后登录时间');
			$table->string('last_login_ip',15)->comment('最后登录ip');
		});
		
		Schema::create('admin_action_log', function (Blueprint $table) {
            $table->increments('id')->comment('日志id');
            $table->text('input')->comment('操作内容');
            $table->tinyInteger('status')->default(1)->comment('是否成功');
            $table->string('path')->comment('操作的路由');
            $table->string('path_name')->commit('操作的路由名称');
            $table->string('ip',15)->comment('操作ip');
            $table->string('method')->commit('请求方式');
            $table->unsignedInteger('action_uid')->commit('操作人');
            $table->text('action_user')->commit('操作人详细资料,防止被删除后找不到详细资料');
            $table->timestamps();
            
        });

        Schema::create('admin_menu', function (Blueprint $table) {
            $table->increments('id')->comment('菜单id');
            $table->string('key')->unique()->comment('菜单索引');
            $table->string('name')->comment('菜单');
            $table->string('introduction',150)->nullable()->comment('简介');
            $table->string('redirect')->nullable()->comment('路由重定向 noredirect该值标示为不在面包屑中添加链接');
            $table->tinyInteger('hidden')->default(0)->comment('是否边栏隐藏,默认为0 不隐藏  1为隐藏(true)');
            $table->tinyInteger('always_show')->default(0)->comment('是否一直显示根路由 默认为0 1为显示(true)');
            $table->tinyInteger('no_cache')->default(0)->comment('是否缓存 默认为0 1为缓存(true)');
            $table->tinyInteger('breadcrumb')->default(0)->comment('是否在面包屑中隐藏 默认为0 1为隐藏(false)');
            $table->tinyInteger('is_external_link')->default('0')->comment('是否外联 默认为0 1为外链(该情况下不使用component)');
            $table->string('external_link')->nullable()->comment('外联地址,是外联的情况下该字段不为空');
            $table->tinyInteger('affix')->default(0)->comment('是否附加到导航 默认为0 1为附加(true)');
            $table->string('icon')->nullable()->comment('图标');
            $table->unsignedInteger('pid')->default(0)->comment('上级菜单');
            $table->string('params')->nullable()->default('')->comment('附加参数');
            $table->unsignedInteger('sort')->default(1)->comment('排序');
            $table->timestamps();
        });

        Schema::create('config', function (Blueprint $table) {
            $table->increments('id')->comment('配置id');
            $table->string('name')->unique()->comment('配置名称');
            $table->string('type')->index()->comment('配置类型');
            $table->string('title')->comment('配置说明');
            $table->string('group')->index()->comment('配置分组');
            $table->string('extra')->nullable()->comment('配置值 - 数字,字符串,密码');
            $table->string('remark')->nullable()->comment('配置说明');
            $table->tinyInteger('status')->default(1)->comment('状态');
            $table->text('value')->nullable()->comment('配置值 - 文本,枚举,编辑器');
            $table->tinyInteger('store')->defaule(0)->comment('是否发送至前端使用');
            $table->tinyInteger('sort')->defaule(1)->comment('排序'); //时间倒序  序号正序
            $table->timestamps();
        });

        Schema::create('dictionary', function (Blueprint $table) {
            $table->increments('id')->comment('字典id');
            $table->string('name')->unique()->comment('字典别名');
            $table->string('title')->index()->comment('字段名称');
            $table->text('value')->nullable()->comment('配置值 - serialize序列化类型');
            $table->timestamps();
        });
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('admin_users');
		Schema::dropIfExists('admin_action_log');
		Schema::dropIfExists('admin_menu');
		Schema::dropIfExists('config');
		Schema::dropIfExists('dictionary');

	}
}
