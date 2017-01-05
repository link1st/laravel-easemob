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

    /***********************   发送消息   **********************************/
    use EasemobMessages;

    /***********************   群管理   **********************************/
    use EasemobGroups;

    /***********************   聊天室管理   **********************************/
    use EasemobRooms;

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

    /**
     * 字符串替换
     *
     * @param $string
     *
     * @return mixed
     */
    protected static function stringReplace($string)
    {
        $string = str_replace('\\', '', $string);
        $string = str_replace(' ', '+', $string);

        return $string;
    }

}