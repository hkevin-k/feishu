<?php

namespace feishu\request\auth;

use feishu\request\Base;

use feishu\top\RequestCheckUtil;

/**
 * 获取用户信息
 */
class AppAccessTokenInternal implements Base
{
    public $url = '/auth/v3/app_access_token/internal';

    public $api_paras = [];

    public $method = 'POST';

    /**
     * @var array
     */
    public $access_token_type = [];

    public $app_id;

    public $app_secret;

    /**
     * @param mixed $app_id
     */
    public function setAppId($app_id)
    {
        $this->app_id = $app_id;
        $this->api_paras['app_id'] = $app_id;
    }

    /**
     * @param mixed $app_secret
     */
    public function setAppSecret($app_secret)
    {
        $this->app_secret = $app_secret;
        $this->api_paras['app_secret'] = $app_secret;
    }


    public function check()
    {
        RequestCheckUtil::checkNotNull($this->app_id, 'app_id');
        RequestCheckUtil::checkNotNull($this->app_secret, 'app_secret');
    }

    public function getApiParas()
    {
        return $this->api_paras;
    }
}