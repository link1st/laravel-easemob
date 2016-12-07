<?php
/**
 * Created by PhpStorm.
 * User: link
 * Date: 2016/12/7
 * Time: 14:10
 */
namespace link1st\Easemob\App;

trait EasemobRooms
{

    /**
     * 获取一个聊天室详情
     *
     * @param $room_id
     *
     * @return mixed
     */
    public function room($room_id)
    {
        $url          = $this->url.'chatrooms/'.$room_id;
        $option       = [];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'GET');
    }


    /**
     * 创建聊天室
     *
     * @param        $room_name
     * @param        $owner_name
     * @param string $room_description
     * @param int    $max_user
     * @param array  $member_users
     *
     * @return mixed
     */
    public function roomCreate($room_name, $owner_name, $room_description = "描述", $max_user = 200, $member_users = [])
    {
        $url    = $this->url.'chatrooms';
        $option = [
            'name'        => $room_name,
            'description' => $room_description,
            'maxusers'    => $max_user,
            'owner'       => $owner_name,
        ];
        if ( ! empty($member_users)) {
            $option['members'] = $member_users;
        }
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'POST');
    }


    /**
     * 删除聊天室
     *
     * @param $room_id
     *
     * @return mixed
     */
    public function roomDel($room_id)
    {
        $url          = $this->url.'chatrooms/'.$room_id;
        $option       = [];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'DELETE');
    }


    /**
     * 修改聊天室信息
     *
     * @param string $group_id
     * @param string $group_name
     * @param string $group_description
     * @param int    $max_user
     *
     * @return mixed
     * @throws EasemobError
     */
    public function roomEdit($room_id, $room_name = "", $room_description = "", $max_user = 0)
    {
        $url    = $this->url.'chatgroups/'.$room_id;
        $option = [
            "name"        => self::stringReplace($room_name),
            "description" => self::stringReplace($room_description),
            "maxusers"    => $max_user,
        ];
        $option = array_filter($option);
        if (empty($option)) {
            throw new EasemobError('提交修改的参数，不修改提交空！');
        }

        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'PUT');
    }


    /**
     * 获取用户所有参加的聊天室
     *
     * @param $user
     *
     * @return mixed
     */
    public function userToRooms($user)
    {
        $url          = $this->url.'users/'.$user.'/joined_chatrooms';
        $option       = [];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'GET');
    }


    /**
     * 聊天室添加成员——批量
     *
     * @param string $room_id
     * @param array $users
     *
     * @return mixed
     */
    public function roomAddUsers($room_id, $users)
    {
        $url          = $this->url.'chatrooms/'.$room_id.'/users';
        $option       = [
            'usernames' => $users
        ];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'POST');
    }


    /**
     * 聊天室删除成员——批量
     *
     * @param string $room_id
     * @param array $users
     *
     * @return mixed
     */
    public function roomDelUsers($room_id, $users)
    {
        $url          = $this->url.'chatrooms/'.$room_id.'/users/'.implode(',', $users);
        $option       = [];
        $access_token = $this->getToken();
        $header []    = 'Authorization: Bearer '.$access_token;

        return Http::postCurl($url, $option, $header, 'DELETE');
    }
}