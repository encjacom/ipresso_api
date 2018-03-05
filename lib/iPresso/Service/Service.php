<?php

namespace iPresso\Service;

class Service
{
    const API_VER = 2;
    const REQUEST_METHOD_GET = 'GET';
    const REQUEST_METHOD_POST = 'POST';
    const REQUEST_METHOD_PUT = 'PUT';
    const REQUEST_METHOD_DELETE = 'DELETE';

    /**
     * @var resource
     */
    private $curlHandler;

    /**
     * @var string
     */
    private $customerKey;

    /**
     * @var array
     */
    private $customHeaders = [];

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var int
     */
    private $iteration = 0;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $post_data = '';

    /**
     * @var string
     */
    private $request_path;

    /**
     * @var string
     */
    private $request_type = self::REQUEST_METHOD_GET;

    /**
     * @var string
     */
    private $token;
    /**
     * @var callable
     */
    private $tokenCallBack;

    /**
     * @var string
     */
    private $url;

    /**
     * @var integer
     */
    private $version = self::API_VER;

    /**
     * Service constructor.
     */
    public function __construct()
    {
        $this->_curlInit();
    }

    /**
     * @param mixed $login
     * @return Service
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @param mixed $password
     * @return Service
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param mixed $token
     * @return Service
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @param mixed $url
     * @return Service
     */
    public function setUrl($url)
    {
        $this->url = $url . '/api/' . $this->version . '/';
        return $this;
    }

    /**
     * @param mixed $customerKey
     * @return Service
     */
    public function setCustomerKey($customerKey)
    {
        $this->customerKey = $customerKey;
        return $this;
    }

    /**
     * @param mixed $request_type
     * @return Service
     */
    public function setRequestType($request_type)
    {
        $this->request_type = $request_type;
        return $this;
    }

    /**
     * @param mixed $request_path
     * @return Service
     */
    public function setRequestPath($request_path)
    {
        $this->request_path = $request_path;
        return $this;
    }

    /**
     * @param array $post_data
     * @return Service
     */
    public function setPostData($post_data)
    {
        $this->post_data = http_build_query($post_data);
        return $this;
    }

    /**
     * @return Service
     */
    public function debug()
    {
        $this->debug = true;
        return $this;
    }

    /**
     * @param int $version
     * @return Service
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @param string $customHeader
     * @return Service
     */
    public function addCustomHeader($customHeader)
    {
        $this->customHeaders[] = $customHeader;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTokenCallBack()
    {
        return $this->tokenCallBack;
    }

    /**
     * @param mixed $tokenCallBack
     * @return $this
     */
    public function setTokenCallBack($tokenCallBack)
    {
        $this->tokenCallBack = $tokenCallBack;
        return $this;
    }

    /**
     * @param bool $getToken
     * @throws \Exception
     */
    private function _check($getToken = false)
    {
        if (!$this->token && !$getToken)
            throw new \Exception('Set token first.');

        if (!$this->login)
            throw new \Exception('Set login first.');

        if (!$this->password)
            throw new \Exception('Set password first.');

        if (!$this->url)
            throw new \Exception('Set url first.');

        if (!$this->customerKey)
            throw new \Exception('Set customerKey first.');
    }


    /**
     * @return bool|Response
     * @throws \Exception
     */
    public function request()
    {
        $this->_check();
        $this->_type();
        $this->_requestHeaders();
        $response = (new Response($this->_exec($this->url . $this->request_path)));
        if (isset($response->code)) {
            switch ($response->code) {
                case Response::STATUS_FORBIDDEN:
                    if (!isset($response->error_code) && $this->iteration < 5 && $this->getToken()) {
                        $this->iteration++;
                        return $this->request();
                    }
                default:
                    return $response;
                    break;
            }
        }
        return false;
    }


    private function _type()
    {
        switch ($this->request_type) {
            case self::REQUEST_METHOD_DELETE:
                curl_setopt($this->curlHandler, CURLOPT_CUSTOMREQUEST, self::REQUEST_METHOD_DELETE);
                break;
            case self::REQUEST_METHOD_POST:
                curl_setopt($this->curlHandler, CURLOPT_POST, true);
                curl_setopt($this->curlHandler, CURLOPT_POSTFIELDS, $this->post_data);
                curl_setopt($this->curlHandler, CURLOPT_CUSTOMREQUEST, self::REQUEST_METHOD_POST);
                break;
            case self::REQUEST_METHOD_PUT:
                curl_setopt($this->curlHandler, CURLOPT_CUSTOMREQUEST, self::REQUEST_METHOD_PUT);
                curl_setopt($this->curlHandler, CURLOPT_POSTFIELDS, $this->post_data);
                break;
            case self::REQUEST_METHOD_GET:
                curl_setopt($this->curlHandler, CURLOPT_CUSTOMREQUEST, self::REQUEST_METHOD_GET);
                break;
            default:
                break;
        }
    }

    /**
     * Use to get session token
     * @param bool $fullResponse
     * @return bool|Response|string
     * @throws \Exception
     */
    public function getToken($fullResponse = false)
    {
        $this->_check(true);
        $this->_headers();
        $this->_auth();
        $response = (new Response($this->_exec($this->url . 'auth/' . $this->customerKey)));

        if ($fullResponse)
            return $response;

        if (isset($response->code) && 200 == $response->code) {

            if (!empty($this->tokenCallBack)) {
                call_user_func($this->tokenCallBack, $response->data);
            }

            $this->token = $response->data;
            return $this->token;
        }
        return false;
    }

    /**
     * @param string $url
     * @return mixed
     */
    private function _exec($url)
    {
        curl_setopt($this->curlHandler, CURLOPT_URL, $url);
        curl_setopt($this->curlHandler, CURLOPT_HTTPHEADER, array_merge($this->headers, $this->customHeaders));
        $jSON = curl_exec($this->curlHandler);

        if ($this->debug) {
            print_r($jSON);
        }

        return json_decode($jSON);
    }

    private function _headers()
    {
        $this->headers = array();
        $this->headers[] = 'ACCEPT: text/json';
        $this->headers[] = 'USER_AGENT: iPresso';
    }

    private function _auth()
    {
        curl_setopt($this->curlHandler, CURLOPT_USERPWD, $this->login . ':' . $this->password);
    }

    private function _requestHeaders()
    {
        $this->_headers();
        $this->headers[] = 'IPRESSO_TOKEN: ' . $this->token;
    }

    private function _curlInit()
    {
        $this->curlHandler = curl_init();
        curl_setopt($this->curlHandler, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($this->curlHandler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curlHandler, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curlHandler, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->curlHandler, CURLOPT_COOKIE, 'XDEBUG_SESSION=1');
    }

    /**
     * @param bool $die
     * @param mixed $variable
     * @param bool $desc
     * @param bool $noHtml
     */
    public static function dump($die, $variable, $desc = false, $noHtml = false)
    {
        if (is_string($variable)) {
            $variable = str_replace("<_new_line_>", "<BR>", $variable);
        }

        if ($noHtml) {
            echo "\n";
        } else {
            echo "<pre>";
        }

        if ($desc) {
            echo $desc . ": ";
        }

        print_r($variable);

        if ($noHtml) {
            echo "";
        } else {
            echo "</pre>";
        }

        if ($die) {
            die();
        }
    }
}