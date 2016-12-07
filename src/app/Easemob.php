<?php
namespace link1st\Easemob\App;

use Config;
use Cache;

class Easemob
{

    // 缓存的名称
    const CACHE_NAME = 'easemob';

    // 接口地址域名
    public $domain_name = null;

    // 企业的唯一标识
    public $org_name = null;

    // “APP”唯一标识
    public $app_name = null;

    // 客户ID
    public $client_id = null;

    // 客户秘钥
    public $client_secret = null;

    // token缓存时间
    public $token_cache_time = null;

    // url地址
    public $url = null;

    // 目标数组 用户，群，聊天室
    public $target_array = [ 'users', 'chatgroups', 'chatrooms' ];


    public function __construct()
    {
        $this->domain_name      = Config::get('easemob.domain_name');
        $this->org_name         = Config::get('easemob.org_name');
        $this->app_name         = Config::get('easemob.app_name');
        $this->client_id        = Config::get('easemob.client_id');
        $this->client_secret    = Config::get('easemob.client_secret');
        $this->token_cache_time = Config::get('easemob.token_cache_time');
        $this->url              = sprintf('%s/%s/%s/', $this->domain_name, $this->org_name, $this->app_name);
    }

    /***********************   注册   **********************************/

    /**
     * 开放注册用户
     *
     * @param        $name      [用户名]
     * @param string $password  [密码]
     * @param string $nick_name [昵称]
     *
     * @return mixed
     */
    public function publicRegistration($name, $password = '', $nick_name = "")
    {
        $url    = $this->url.'users';
        $option = [
            'username' => $name,
            'password' => $password,
            'nickname' => $nick_name,
        ];

        return Http::postCurl($url, $option, 0);
    }


    /**
     * 授权注册用户
     *
     * @param        $name      [用户名]
     * @param string $password  [密码]
     * @param string $nick_name [昵称]
     *
     * @return mixed
     */
    public function authorizationRegistration($name, $password = '123456')
    {
        $url          = $this->url.'users';
        $option       = [
            'username' => $name,
            'password' => $password,
        ];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header);
    }


    /**
     * 授权注册用户——批量
     * 密码不为空
     *
     * @param    array $users [用户名 包含 username,password的数组]
     *
     * @return mixed
     */
    public function authorizationRegistrations($users)
    {
        $url          = $this->url.'users';
        $option       = $users;
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header);
    }

    /***********************   用户操作   **********************************/

    /**
     * 获取单个用户
     *
     * @param $user_name
     *
     * @return mixed
     */
    public function getUser($user_name)
    {
        $url          = $this->url.'users/'.$user_name;
        $option       = [];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'GET');
    }


    /**
     * 获取所有用户
     *
     * @param int    $limit  [显示条数]
     * @param string $cursor [光标，在此之后的数据]
     *
     * @return mixed
     */
    public function getUserAll($limit = 10, $cursor = '')
    {
        $url          = $this->url.'users';
        $option       = [
            'limit'  => $limit,
            'cursor' => $cursor
        ];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'GET');
    }


    /**
     * 删除用户
     * 删除一个用户会删除以该用户为群主的所有群组和聊天室
     *
     * @param $user_name
     *
     * @return mixed
     */
    public function delUser($user_name)
    {
        $url          = $this->url.'users/'.$user_name;
        $option       = [];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'DELETE');
    }


    /**
     * 修改密码
     *
     * @param $user_name
     * @param $new_password [新密码]
     *
     * @return mixed
     */
    public function editUserPassword($user_name, $new_password)
    {
        $url          = $this->url.'users/'.$user_name.'/password';
        $option       = [
            'newpassword' => $password
        ];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'PUT');
    }


    /**
     * 修改用户昵称
     * 只能在后台看到，前端无法看见这个昵称
     *
     * @param $user_name
     * @param $nickname
     *
     * @return mixed
     */
    public function editUserNickName($user_name, $nickname)
    {
        $url          = $this->url.'users/'.$user_name;
        $option       = [
            'nickname' => $nickname
        ];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'PUT');
    }


    /**
     * 强制用户下线
     *
     * @param $user_name
     *
     * @return mixed
     */
    public function disconnect($user_name)
    {
        $url          = $this->url.'users/'.$user_name.'/disconnect';
        $option       = [];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'GET');
    }


    /***********************   好友操作   **********************************/

    /**
     * 给用户添加好友
     *
     * @param $owner_username  [主人]
     * @param $friend_username [朋友]
     *
     * @return mixed
     */
    public function addFriend($owner_username, $friend_username)
    {
        $url          = $this->url.'users/'.$owner_username.'/contacts/users/'.$friend_username;
        $option       = [];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'POST');
    }


    /**
     * 给用户删除好友
     *
     * @param $owner_username  [主人]
     * @param $friend_username [朋友]
     *
     * @return mixed
     */
    public function delFriend($owner_username, $friend_username)
    {
        $url          = $this->url.'users/'.$owner_username.'/contacts/users/'.$friend_username;
        $option       = [];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'DELETE');
    }


    /**
     * 查看用户所以好友
     *
     * @param $user_name
     *
     * @return mixed
     */
    public function showFriends($user_name)
    {
        $url          = $this->url.'users/'.$user_name.'/contacts/users/';
        $option       = [];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'GET');
    }

    /***********************   文件上传下载   **********************************/

    /**
     * 上传文件
     *
     * @param $file_path
     *
     * @return mixed
     * @throws EasemobError
     */
    public function uploadFile($file_path)
    {
        if ( ! is_file($file_path)) {
            throw new EasemobError('文件不存在', 404);
        }
        $url = $this->url.'chatfiles';

        $curl_file    = curl_file_create($file_path);
        $option       = [
            'file' => $curl_file,
        ];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'POST');
    }


    /**
     * 下载文件
     *
     * @param $uuid         [uuid]
     * @param $share_secret [秘钥]
     *
     * @return mixed
     */
    public function downloadFile($uuid, $share_secret)
    {
        $url = $this->url.'chatfiles/'.$uuid;

        $option       = [];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;
        $header []    = 'share-secret: '.$share_secret;

        return Http::postCurl($url, $option, $header, 'GET', 10, false);
    }


    /***********************   发送消息   **********************************/

    /**
     * 发送文本消息
     *
     * @param array  $users       [接收的对象数组]
     * @param string $target_type [类型]
     * @param string $message     [内容]
     * @param string $send_user   [消息发送者]
     * @param array  $ext         [消息扩展体]
     *
     * @return mixed
     * @throws EasemobError
     */
    public function sendMessageText($users, $target_type = 'users', $message = "", $send_user = 'admin', $ext = [])
    {
        if ( ! in_array($target_type, $this->target_array)) {
            throw new EasemobError('target_type 参数错误！');
        }

        $url          = $this->url.'messages';
        $option       = [
            'target_type' => $target_type,
            'target'      => $users,
            'msg'         => [
                'type' => 'txt',
                'msg'  => $message
            ],
            'from'        => $send_user
        ];

        // 是否有消息扩展
        if(!empty($ext)) {
            $option['ext'] = $ext;
        }

        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'POST');
    }


    /**
     * 发送图片消息
     *
     * @param array  $users        [接收的对象数组]
     * @param string $target_type  [类型]
     * @param string $uuid         [文件的uuid]
     * @param string $share_secret [文件的秘钥 上传后生产]
     * @param string $file_name    [指定文件名]
     * @param int    $width        [宽]
     * @param int    $height       [高]
     * @param string $send_user
     *
     * @return mixed
     * @throws EasemobError
     */
    public function sendMessageImg($users, $target_type = 'users', $uuid, $share_secret, $file_name, $width = 480, $height = 720, $send_user = 'admin')
    {
        if ( ! in_array($target_type, $this->target_array)) {
            throw new EasemobError('target_type 参数错误！');
        }

        $url          = $this->url.'messages';
        $option       = [
            'target_type' => $target_type,
            'target'      => $users,
            'msg'         => [
                'type'     => 'img',
                'url'      => $this->url.'chatfiles/'.$uuid,
                'filename' => $file_name,
                'secret'   => $share_secret,
                'size'     => [
                    'width'  => $width,
                    'height' => $height
                ]
            ],
            'from'        => $send_user
        ];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'POST');
    }


    /**
     * 发送语音消息
     *
     * @param array  $users        [接收的对象数组]
     * @param string $target_type  [类型]
     * @param string $uuid         [文件的uuid]
     * @param string $share_secret [文件的秘钥 上传后生产]
     * @param string $file_name    [指定文件名]
     * @param int    $length       [长度]
     * @param string $send_user
     *
     * @return mixed
     * @throws EasemobError
     */
    public function sendMessageAudio($users, $target_type = 'users', $uuid, $share_secret, $file_name, $length = 10, $send_user = 'admin')
    {
        if ( ! in_array($target_type, $this->target_array)) {
            throw new EasemobError('target_type 参数错误！');
        }

        $url          = $this->url.'messages';
        $option       = [
            'target_type' => $target_type,
            'target'      => $users,
            'msg'         => [
                'type'     => 'audio',
                'url'      => $this->url.'chatfiles/'.$uuid,
                'filename' => $file_name,
                'secret'   => $share_secret,
                'length'   => $length
            ],
            'from'        => $send_user
        ];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'POST');
    }

    /**
     * 发送视频消息
     *
     * @param array  $users
     * @param string $target_type  [类型]
     * @param string $uuid         [文件的uuid]
     * @param string $share_secret [文件的秘钥 上传后生产]
     * @param string $file_name    [指定文件名]
     * @param int    $length       [长度]
     * @param string $send_user
     *
     * @return mixed
     * @throws EasemobError
     */
    /**
     * 发送视频消息
     *
     * @param array  $users              [接收的对象数组]
     * @param string $target_type        [类型]
     * @param        $video_uuid         [视频uuid]
     * @param        $video_share_secret [视频秘钥]
     * @param        $video_file_name    [下载的时候视频名称]
     * @param int    $length             [长度]
     * @param int    $video_length       [视频大小]
     * @param        $img_uuid           [缩略图]
     * @param        $img_share_secret   [图片秘钥]
     * @param string $send_user          [发送者]
     *
     * @return mixed
     * @throws EasemobError
     */
    public function sendMessageVideo($users, $target_type = 'users', $video_uuid, $video_share_secret, $video_file_name, $length = 10, $video_length = 58103, $img_uuid, $img_share_secret, $send_user = 'admin')
    {
        if ( ! in_array($target_type, $this->target_array)) {
            throw new EasemobError('target_type 参数错误！');
        }

        $url          = $this->url.'messages';
        $option       = [
            'target_type' => $target_type,
            'target'      => $users,
            'msg'         => [
                'type'         => 'video',
                'url'          => $this->url.'chatfiles/'.$video_uuid,
                'filename'     => $video_file_name,
                'thumb_secret' => $video_share_secret,
                'length'       => $length,
                'file_length'  => $video_length,
                'thumb'        => $this->url.'chatfiles/'.$img_uuid,
                'secret'       => $img_share_secret
            ],
            'from'        => $send_user
        ];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'POST');
    }


    /**
     * 消息透传
     *
     * @param array  $users       [接收的对象数组]
     * @param string $target_type [类型]
     * @param string $action      [内容]
     * @param string $send_user   [消息发送者]
     *
     * @return mixed
     * @throws EasemobError
     */
    public function sendMessagePNS($users, $target_type = 'users', $action = "", $send_user = 'admin')
    {
        if ( ! in_array($target_type, $this->target_array)) {
            throw new EasemobError('target_type 参数错误！');
        }

        $url          = $this->url.'messages';
        $option       = [
            'target_type' => $target_type,
            'target'      => $users,
            'msg'         => [
                'type'   => 'cmd',
                'action' => $action
            ],
            'from'        => $send_user
        ];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'POST');
    }

    /***********************   群管理   **********************************/

    /***********************   聊天室管理   **********************************/

    /***********************   token操作   **********************************/

    /**
     * 返回token
     *
     * @return mixed
     */
    public function getToken()
    {
        if (Cache::has(self::CACHE_NAME)) {
            return Cache::get(self::CACHE_NAME);
        } else {
            $url    = $this->url."token";
            $option = [
                'grant_type'    => 'client_credentials',
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
            ];
            $return = Http::postCurl($url, $option);
            Cache::put(self::CACHE_NAME, $return['access_token'], (int) ($return['expires_in'] / 60));

            return $return['access_token'];

        }
    }

}

;
