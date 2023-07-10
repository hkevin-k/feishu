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
     * @var string 【必填】 消息接收者id类型 open_id/user_id/union_id/email/chat_id
     * 可选值有：open_id：标识一个用户在某个应用中的身份。同一个用户在不同应用中的 Open ID 不同
     *         user_id：标识一个用户在某个租户内的身份。同一个用户在租户 A 和租户 B 内的 User ID 是不同的。在同一个租户内，一个用户的 User ID 在所有应用（包括商店应用）中都保持一致。User ID 主要用于在不同的应用间打通用户数
     *         union_id：标识一个用户在某个应用开发商下的身份。同一用户在同一开发商下的应用中的 Union ID 是相同的，在不同开发商下的应用中的 Union ID 是不同的。通过 Union ID，应用开发商可以把同个用户在多个应用中的身份关联起来
     *         email：以用户的真实邮箱来标识用户。
     *         chat_id：以群ID来标识群聊。
     */
    public $receive_id_type;

    /**
     * @var string 【必填】 依据receive_id_type的值，填写对应的消息接收者id
     */
    public $receive_id;

    /**
     * @var string 【必填】 消息内容，json结构序列化后的字符串。不同msg_type对应不同内容。消息类型 包括：text、post、image、file、audio、media、sticker、interactive、share_chat、share_user等
     */
    public $content;

    /**
     * @var string 【必填】 消息类型 包括：text、post、image、file、audio、media、sticker、interactive、share_chat、share_user等
     */
    public $msg_type;

    /**
     * @var string 【选填】 由开发者生成的唯一字符串序列，用于发送消息请求去重；持有相同uuid的请求1小时内至多成功发送一条消息
     */
    public $uuid;

    /**
     * @param string $receive_id
     */
    public function setReceiveId($receive_id)
    {
        $this->receive_id = $receive_id;
        $this->api_paras['receive_id'] = $receive_id;
    }

    /**
     * @param string $receive_id_type
     */
    public function setReceiveIdType($receive_id_type)
    {
        $this->receive_id_type = $receive_id_type;
        $this->api_paras['receive_id_type'] = $receive_id_type;
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

    /**
     * @param string $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
        $this->api_paras['uuid'] = $uuid;
    }
}