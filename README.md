# laravel-easemob
环信即时通讯laravel包开发，用于环信用户、群、聊天室等功能

## 安装
加载包

`"link1st/laravel-easemob": "dev-master"`

或

`composer require link1st/laravel-easemob`

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

### 强制用户下线
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
### 获取群信息
`\Easemob::groups($group_ids);`

### 新建群
`\Easemob::groupCreate($group_name, $group_description = '描述', $owner_user, $members_users = [], $is_public = true, $max_user = 200, $is_approval = true)`

### 修改群信息
`\Easemob::groupEdit($group_id, $group_name = "", $group_description = "", $max_user = 0)`

### 删除群
`\Easemob::groupDel($group_id)`

### 获取所有群成员
`\Easemob::groupUsers($group_id)`

### 添加群成员
`\Easemob::groupAddUsers($group_id, $users)`

### 删除群成员
`\Easemob::groupDelUsers($group_id, $users)`

### 获取用户所以参加的群
`\Easemob::userToGroups($user)`

### 群转让
`\Easemob::groupTransfer($group_id, $new_owner_user)`

- - -
### 查看聊天室详情
`\Easemob::room($room_id)`

### 创建聊天室
`\Easemob::roomCreate($room_name, $owner_name, $room_description = "描述", $max_user = 200, $member_users = [])`

### 删除聊天室
`\Easemob::roomDel($room_id)`

### 修改聊天室信息
`\Easemob::roomEdit($room_id, $room_name = "", $room_description = "", $max_user = 0)`

### 获取用户参加的聊天室
`\Easemob::userToRooms($user)`

### 聊天室添加成员
`\Easemob::roomAddUsers($room_id, $users)`


### 聊天室删除成员
`\Easemob::roomDelUsers($room_id, $users)`

