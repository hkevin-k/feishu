<?php

namespace feishu\request\im;

use feishu\request\BaseInterface;
use feishu\request\BaseClass;

use feishu\top\RequestCheckUtil;

/**
 * 发送消息
 */
class Messages extends BaseClass implements BaseInterface
{
    public $url = '/im/v1/messages';

    public $method = 'POST';

    /**
     * @var array
     */
    public $access_token_type = [
        'tenant_access_token',
    ];

    /**
     * 验证方法
     */
    public function check()
    {
        RequestCheckUtil::checkNotNull($this->receive_id, 'receive_id');
        RequestCheckUtil::checkNotNull($this->content, 'content');
        RequestCheckUtil::checkNotNull($this->msg_type, 'msg_type');
    }

    /**
     * @var string 依据receive_id_type的值，填写对应的消息接收者id
     */
    public $receive_id;

    /**
     * @var string 消息内容，json结构序列化后的字符串。不同msg_type对应不同内容。消息类型 包括：text、post、image、file、audio、media、sticker、interactive、share_chat、share_user等
     */
    public $content;

    /**
     * @var string 消息类型 包括：text、post、image、file、audio、media、sticker、interactive、share_chat、share_user等
     */
    public $msg_type;

    /**
     * @param string $receive_id
     */
    public function setReceiveId($receive_id)
    {
        $this->receive_id = $receive_id;
        $this->api_paras['receive_id'] = $receive_id;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
        $this->api_paras['content'] = $content;
    }

    /**
     * @param string $msg_type
     */
    public function setMsgType($msg_type)
    {
        $this->msg_type = $msg_type;
        $this->api_paras['msg_type'] = $msg_type;
    }
}