<?php

namespace feishu\request\contact;

use feishu\request\BaseClass;
use feishu\request\BaseInterface;

use feishu\top\RequestCheckUtil;

/**
 * 获取部门信息列表
 */
class Departments extends BaseClass implements BaseInterface
{
    public $url = '/contact/v3/departments';

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
     * 父部门的ID，填上获取部门下所有子部门，此处填写的 ID 必须是 department_id_type 指定的 ID。
     * @var string
     */
    public $parent_department_id;

    /**
     * 是否递归获取子部门
     * 示例值：是否递归获取子部门，默认值：false
     * @var boolean
     */
    public $fetch_child;

    /**
     * 分页标记，第一次请求不填，表示从头开始遍历；分页查询结果还有更多项时会同时返回新的 page_token，下次遍历可采用该 page_token 获取查询结果
     * @var string
     */
    public $page_token;

    /**
     * 分页大小
     * @var string
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
     * @param string $parent_department_id
     */
    public function setParentDepartmentId($parent_department_id)
    {
        $this->parent_department_id = $parent_department_id;
        $this->api_paras['parent_department_id'] = $parent_department_id;
    }

    /**
     * @param bool $fetch_child
     */
    public function setFetchChild($fetch_child)
    {
        $this->fetch_child = $fetch_child;
        $this->api_paras['fetch_child'] = $fetch_child;
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
     * @param string $page_size
     */
    public function setPageSize($page_size)
    {
        $this->page_size = $page_size;
        $this->api_paras['page_size'] = $page_size;
    }

    public function check()
    {
        RequestCheckUtil::checkMaxValue($this->page_size, 50, 'page_size');
    }
}