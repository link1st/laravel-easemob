<?php namespace link1st\Easemob;

use Config ;

class Easemob
{

    /**
     * 获取配置项
     * @return int
     */
    function get_config(){
        return Config::get('easemob.EASEMOB_DOMAIN',"空");
    }


    /**
     * 默认
     * @return mixed
     */
    public function index(){
        return 'index';
    }

};
