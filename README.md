# laravel-easemob
环信及时通讯laravel包开发，用于环信用户、群、聊天室等功能

## 安装
加载包

`"link1st/laravel-test": "dev-master"`

在配置文件中添加 **config/app.php**

```php
    'providers' => [
        /**
         * 添加供应商
         */
        link1st\test\TestServiceProvider::class,
    ],
    'aliases' => [
         /**
          * 添加别名
          */
        'test'=>link1st\test\Facades\Test::class,
    ],
```

生成配置文件

`php artisan vendor:publish`

## 使用
```php
    // 使用自动加载直接使用
    $link = new \link1st\test\easemob();
    echo $link->get_config();
    
    // 使用门面使用
    echo \test::get_config(); 
    echo \test::index();
```