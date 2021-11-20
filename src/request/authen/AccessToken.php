<?php

namespace feishu\request\authen;

use feishu\request\Base;

use feishu\top\RequestCheckUtil;

/**
 * 获取登录用户身份
 */
class AccessToken implements Base
{
    public $url = '/authen/v1/access_token';

    public $api_paras = [];

    /**
     * @var string
     */
    public $method = 'POST';

    /**
     * @var array
     */
    public $access_token_type = [
        'app_access_token'
    ];

    /**
     * 授权类型，本流程中，此值为："authorization_code"
     * 示例值："authorization_code"
     * 必填
     * @var string
     */
    public $grant_type = 'authorization_code';

    /**
     * 来自请求身份验证流程，用户扫码登录后会自动302到redirect_uri并带上此参数
     * 示例值："xMSldislSkdK"
     * 必填
     * @var string
     */
    public $code;

    /**
     * @param string $grant_type
     */
    public function setGrantType($grant_type)
    {
        $this->grant_type = $grant_type;
        $this->api_paras['grant_type'] = $grant_type;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
        $this->api_paras['code'] = $code;
    }

    public function check()
    {
        RequestCheckUtil::checkNotNull($this->grant_type, 'grant_type');
        RequestCheckUtil::checkNotNull($this->code, 'code');
    }

    public function getApiParas()
    {
        $this->api_paras['grant_type'] = $this->grant_type;
        return $this->api_paras;
    }
}