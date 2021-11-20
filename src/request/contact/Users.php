<?php

namespace feishu\request\contact;

use feishu\request\Base;

use feishu\top\RequestCheckUtil;

/**
 * 获取用户列表
 */
class Users implements Base
{
    public $url = '/contact/v3/users';

    public $api_paras = [];

    public $method = 'GET';

    /**
     * @var array
     */
    public $access_token_type = [
        'tenant_access_token',
        'user_access_token'
    ];

    /**
     * 用户 ID 类型
     * @var string
     */
    public $user_id_type;

    /**
     * 此次调用中使用的部门ID的类型
     * @var string
     */
    public $department_id_type;

    /**
     * 填写该字段表示获取部门下所有用户，选填。
     * @var string
     */
    public $department_id;

    /**
     * 分页标记，第一次请求不填，表示从头开始遍历；分页查询结果还有更多项时会同时返回新的 page_token，下次遍历可采用该 page_token 获取查询结果
     * @var string
     */
    public $page_token;

    /**
     * 分页大小
     * @var int
     */
    public $page_size;

    /**
     * @param string $user_id_type
     */
    public function setUserIdType($user_id_type)
    {
        $this->user_id_type = $user_id_type;
        $this->api_paras['user_id_type'] = $user_id_type;
    }

    /**
     * @param string $department_id_type
     */
    public function setDepartmentIdType($department_id_type)
    {
        $this->department_id_type = $department_id_type;
        $this->api_paras['department_id_type'] = $department_id_type;
    }

    /**
     * @param string $department_id
     */
    public function setDepartmentId($department_id)
    {
        $this->department_id = $department_id;
        $this->api_paras['department_id'] = $department_id;
    }

    /**
     * @param string $page_token
     */
    public function setPageToken($page_token)
    {
        $this->page_token = $page_token;
        $this->api_paras['page_token'] = $page_token;
    }

    /**
     * @param int $page_size
     */
    public function setPageSize($page_size)
    {
        $this->page_size = $page_size;
        $this->api_paras['page_size'] = $page_size;
    }

    public function check() {
        RequestCheckUtil::checkMaxValue($this->page_size, 100, 'page_size');
    }

    public function getApiParas() {
        return $this->api_paras;
    }
}