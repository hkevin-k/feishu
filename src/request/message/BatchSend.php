<?php

namespace feishu\request\message;

use feishu\request\BaseInterface;
use feishu\request\BaseClass;

use feishu\top\RequestCheckUtil;

/**
 * 批量发送消息
 *
 * content, card字段两个字段至少填一个
 * department_ids, open_ids, user_ids 三个字段至少填一个
 */
class BatchSend extends BaseClass implements BaseInterface
{
    public $url = '/message/v4/batch_send/';

    public $method = 'POST';

    /**
     * @var array
     */
    public $access_token_type = [
        'tenant_access_token',
    ];

    public function check()
    {
        RequestCheckUtil::checkNotNull($this->msg_type, 'msg_type');
        RequestCheckUtil::checkArray($this->content, 'content');
        RequestCheckUtil::checkArray($this->card, 'card');
        RequestCheckUtil::checkArray($this->department_ids, 'department_ids');
        RequestCheckUtil::checkArray($this->open_ids, 'open_ids');
        RequestCheckUtil::checkArray($this->user_ids, 'user_ids');
    }

    /**
     * @var string 消息类型，支持多种消息类型，
     */
    public $msg_type;

    /**
     * @var array 消息内容，支持除卡片消息外的多种消息内容
     */
    public $content;

    /**
     * @var array 卡片消息内容，注意card和content必须二选一
     */
    public $card;

    /**
     * @var array 支持自定义部门ID，和open_department_id，列表长度小于等于 200
     */
    public $department_ids;

    /**
     * @var array 用户 open_id 列表，长度小于等于 200
     */
    public $open_ids;

    /**
     * @var array 用户 user_id 列表，长度小于等于 200 （对应 V3 接口的 employee_ids ）
     */
    public $user_ids;

    /**
     * @param string $msg_type
     */
    public function setMsgType($msg_type)
    {
        $this->msg_type = $msg_type;
        $this->api_paras['msg_type'] = $msg_type;
    }

    /**
     * @param array $content
     */
    public function setContent($content)
    {
        $this->content = $content;
        $this->api_paras['content'] = $content;
    }

    /**
     * @param array $card
     */
    public function setCard($card)
    {
        $this->card = $card;
        $this->api_paras['card'] = $card;
    }

    /**
     * @param array $department_ids
     */
    public function setDepartmentIds($department_ids)
    {
        $this->department_ids = $department_ids;
        $this->api_paras['department_ids'] = $department_ids;
    }

    /**
     * @param array $open_ids
     */
    public function setOpenIds($open_ids)
    {
        $this->open_ids = $open_ids;
        $this->api_paras['open_ids'] = $open_ids;
    }

    /**
     * @param array $user_ids
     */
    public function setUserIds($user_ids)
    {
        $this->user_ids = $user_ids;
        $this->api_paras['user_ids'] = $user_ids;
    }
}