<?php
namespace link1st\Easemob;

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


    public function __construct()
    {
        $this->domain_name      = Config::get('easemob.domain_name');
        $this->org_name         = Config::get('easemob.org_name');
        $this->app_name         = Config::get('easemob.app_name');
        $this->client_id        = Config::get('easemob.client_id');
        $this->client_secret    = Config::get('easemob.client_secret');
        $this->token_cache_time = Config::get('easemob.token_cache_time');
    }


    /**
     * 获取配置项
     * @return int
     */
    function get_config()
    {
        return Config::get('easemob.EASEMOB_DOMAIN', "空");
    }


    /**
     * 默认
     * @return mixed
     */
    public function index()
    {
        return 'index';
    }


    public function getToken()
    {
        $url    = $this->domain_name."/easemob-demo/chatdemoui/token";
        $option = [
            'grant_type'    => 'client_credentials',
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret,
        ];
        $return = Http::postCurl($url,$option);
        dump($return);
        return $return->access_token;
        //// 获取token
        //$token = Cache::remember(self::CACHE_NAME, $this->token_cache_time, function () {
        //    $url    = $this->domain_name."/easemob-demo/chatdemoui/token";
        //    $option = [
        //        'grant_type'    => 'client_credentials',
        //        'client_id'     => $this->client_id,
        //        'client_secret' => $this->client_secret,
        //    ];
        //    $return = Http::postCurl($url,$option);
        //    dump($return);
        //    return $return->access_token;
        //});

    }

}

;
