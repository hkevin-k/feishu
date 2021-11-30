<?php

namespace feishu\request;

/**
 * 基础父类
 */
class BaseClass
{
    /**
     * @var string 请求的地址 每个继承的request都要独自设置
     */
    public $url = '';

    /**
     * @var array 请求接口时的参数
     */
    public $api_paras = [];

    /**
     * @var array 查询参数
     */
    public $params = [];

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getApiParas()
    {
        return $this->api_paras;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $uri = empty($this->params) ? '' : '?'.http_build_query($this->params);
        return $this->url.$uri;
    }
}