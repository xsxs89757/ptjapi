##  ptjapi
### [前端](https://github.com/xsxs89757/admin-ptjapi)
#### 1.安装
> 查看.env配置文件并修改数据库 redis等相关资料
> `composer install` 安装轮子
  `php artisan key:generate` 生成项目令牌
  `php artisan migrate --seed` 安装基础表
#### 2.laravel-echo-server
>`npm install -g laravel-echo-server` 安装laravel-echo-server
  修改laravel-echo-server.json  相关配置
 `laravel-echo-server start` //启动laravel-echo-server
#### 3.启动广播监听
> `php artisan queue:work --tries=3 --timeout=0` --tries 尝试次数。--timeout 过期时间
