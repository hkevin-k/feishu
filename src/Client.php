<?php

namespace feishu;

class Client
{
    protected $domain;
    protected $app_id;
    protected $app_secret;

    public function __construct($config = [])
    {
        $default_config = [
            'domain' => 'https://open.feishu.cn/open-apis',
            'app_id' => '',
            'app_secret' => ''
        ];
        $new_config = array_merge($default_config, array_intersect_key($config, $default_config));

        foreach ($new_config as $key => $value) {
            $this->$key = $value;
        }

        foreach ($this->_access_token_info as $key => $value) {
            $this->_access_token_info[$key] = [
                'token' => '',
                'expire_time' => 0,
            ];
        }
    }

    /**
     * 各种token的信息，包含token和过期信息
     * @var array
     */
    private $_access_token_info = [
        'app_access_token' => [],
        'tenant_access_token' => [],
        'user_access_token' => [],
    ];

    /**
     * 调用执行的方法
     * @param $request
     * @return array|mixed
     */
    public function execute($request) {
        try {
            // 获取token
            $access_token = $this->requestAccessToken($request->access_token_type);
            // 验证请求参数
            $request->check();
            // 请求
            $res = $this->curl($request, $access_token);
            return $res;
        } catch (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'msg' => $e->getMessage()
            ];
        }
    }

    /**
     * 请求方法
     * @param object $request 请求的对象
     * @param string $access_token
     * @return mixed
     * @throws \Exception
     */
    private function curl($request, $access_token) {
        $url = $this->domain . $request->getUrl();
        $ch = curl_init();

        $httpheader = [
            'Content-Type: application/json; charset=utf-8'
        ];

        if ($access_token) {
            $httpheader[] = "Authorization: Bearer {$access_token}";
        }

        if ($request->method == 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request->getApiParas()));
        } else if ($request->method == 'GET') {
            $url .= '?';
            foreach ($request->getApiParas() as $query_key => $query_value) {
                $url .= "$query_key=" . urlencode($query_value) . "&";
            }
            $url = substr($url, 0, -1);
        }

        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $request->method,
            CURLOPT_HTTPHEADER => $httpheader,
        ));
        // https 请求
        if(strlen($url) > 5 && strtolower(substr($url,0,5)) == 'https' ) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch))
        {
            throw new \Exception(curl_error($ch),0);
        }
        else
        {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode)
            {
                throw new \Exception($response, $httpStatusCode);
            }
        }

        curl_close($ch);
        $result = json_decode($response, true);
        return $result ? $result : $response;
    }

    /**
     * 依据 reques 里的类型获取token，不存在就进行请求获取
     * @param array $access_token_type 获取token的类型
     * @return mixed|string
     * @throws \Exception
     */
    private function requestAccessToken($access_token_type)
    {
        $access_token = '';
        if (!empty($access_token_type)) {
            $access_token_info = $this->getValidAccessTokenInfo($access_token_type);
            if (empty($access_token_info)) {
                // 请求token
                $request = new \feishu\request\auth\AppAccessToken();
                $request->setAppId($this->app_id);
                $request->setAppSecret($this->app_secret);
                $res = $this->execute($request);
                if (isset($res['code']) && $res['code'] == 0) {
                    // 设置token
                    foreach (['app_access_token', 'tenant_access_token'] as $key) {
                        $this->setAccessToken($key, $res[$key], time()+$res['expire']);
                    }
                } else {
                    throw new \Exception($res['msg'], $res['code']);
                }
            }
            // 用第一个
            $access_token_info = $this->getValidAccessTokenInfo($access_token_type);
            if (isset($access_token_info)) {
                $access_token = array_values($access_token_info)[0]['token'];
            } else {
                throw new \Exception('Get access token error, access_token_info is empty');
            }
        }
        return $access_token;
    }

    /**
     * 获取有效的access token信息
     * @param array $access_token_type 用于匹配需要的token类型
     * @return array
     */
    private function getValidAccessTokenInfo($access_token_type)
    {
        $access_token_info = array_intersect_key($this->_access_token_info, array_fill_keys($access_token_type, 'feishu'));
        return array_filter($access_token_info, function ($info) {
            if (
                empty($info['token'])
                ||
                (time() >= $info['expire_time'])
            ) {
                return false;
            }
            return true;
        });
    }

    /**
     * 设置token信息
     * @param string $key token类型
     * @param string $token
     * @param int $expire_time 过期时间
     */
    public function setAccessToken($token_type, $token, $expire_time) {
        $this->_access_token_info[$token_type]['token'] = $token;
        $this->_access_token_info[$token_type]['expire_time'] = $expire_time;
    }

    /**
     * @return array
     */
    public function getAccessTokenInfo()
    {
        return $this->_access_token_info;
    }

    /**
     * 返回 用户身份验证 链接
     * @param string $redirect_uri 重定向URL
     * @param string $state 用来维护请求和回调状态的附加字符串，在授权完成回调时会附加此参数，应用可以根据此字符串来判断上下文关系
     * @return array
     */
    public function generateAuthenRedirectUrl($redirect_uri, $state = '')
    {
        $redirect_uri = urlencode($redirect_uri);
        if (empty($state)) {
            $state = time();
        }
        return [
            'url' => "{$this->domain}/authen/v1/index?redirect_uri={$redirect_uri}&app_id={$this->app_id}&state={$state}",
            'state' => $state
        ];
    }
}
