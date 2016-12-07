# laravel-easemob
环信及时通讯laravel包开发，用于环信用户、群、聊天室等功能

## 安装
加载包

`"link1st/laravel-easemob": "dev-master"`

在配置文件中添加 **config/app.php**

```php
    'providers' => [
        /**
         * 添加供应商
         */
        link1st\Easemob\EasemobServiceProvider::class,
    ],
    'aliases' => [
         /**
          * 添加别名
          */
        'Easemob' => link1st\Easemob\Facades\Easemob::class,
    ],
```

生成配置文件

`php artisan vendor:publish`

设置环信的参数 **config/easemob.php**


## 使用
- - -
### 获取token
`\Easemob::getToken();`

- - -
### 开放注册用户
`$user = \Easemob::publicRegistration('xiaoming1');`

### 授权注册 同一个用户只能注册一次
`$user = \Easemob::authorizationRegistration('xiaoming1');`

### 批量注册
```php
$users = [
    ['username'=>'xiaoming2','password'=>1],
    ['username'=>'xiaoming3','password'=>1],
];
$user = \Easemob::authorizationRegistrations($users);
```

- - -
### 获取用户
`$user = \Easemob::getUser('xiaoming1');`

### 获取app所有的用户
```php
$user = \Easemob::getUserAll(100,'LTgzNDAxMjM3OTprcFJFRUpzdUVlYWh5V1UwQjNSbldR');
```

### 删除用户
`$user = \Easemob::delUser('xiaoming1');`

### 修改用户密码
`$user = \Easemob::editUserPassword('xiaoming2',111);`

### 修改昵称
`$user = \Easemob::editUserNickName('xiaoming2',11);`

### 强制用户下载
`$user = \Easemob::disconnect('xiaoming2');`

### 添加好友
`$user = \Easemob::addFriend('xiaoming2','xiaoming3');`

### 删除用户
`$user = \Easemob::delFriend('xiaoming2','xiaoming3');`

### 显示用户好友
`$user = \Easemob::showFriends('xiaoming2');`

- - -
### 上传文件
`\Easemob::uploadFile($file_path);`

### 下载文件
`\Easemob::downloadFile($uuid, $share_secret);`

- - -
### 发送文本消息
`\Easemob::sendMessageText($users, $target_type = 'users', $message = "", $send_user = 'admin', $ext = []);`

### 发送图片消息
`\Easemob::sendMessageImg($users, $target_type = 'users', $uuid, $share_secret, $file_name, $width = 480, $height = 720, $send_user = 'admin');`

### 发送语音消息
`\Easemob::sendMessageAudio($users, $target_type = 'users', $uuid, $share_secret, $file_name, $length = 10, $send_user = 'admin');`

### 发送视频消息
`\Easemob::sendMessageVideo($users, $target_type = 'users', $video_uuid, $video_share_secret, $video_file_name, $length = 10, $video_length = 58103, $img_uuid, $img_share_secret, $send_user = 'admin');`

### 消息透传
`\Easemob::sendMessagePNS($users, $target_type = 'users', $action = "", $send_user = 'admin');`

- - - 
### 新建群
`\Easemob::downloadFile($uuid, $share_secret);`





