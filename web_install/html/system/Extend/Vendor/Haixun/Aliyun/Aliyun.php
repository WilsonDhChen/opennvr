<?php


abstract class Aliyun
{

    //阿里云颁发给用户的访问服务所用的密钥ID
    const ACCESS_KEY_ID = 'LTAI3soctAjn6sg5';
    //加密签名字符串和服务器端验证签名字符串的密钥
    const ACCESS_KEY_SECRET = 'hfqJbJIafkvcHW68wAYxlMkrXhMz8C';

    protected $Format           = 'XML';
    protected $Version          = '2016-09-27';
    protected $AccessKeyId      = '';
    protected $SignatureMethod  = 'HMAC-SHA1';
    protected $Timestamp        = '';
    protected $SignatureVersion = '1.0';
    protected $SignatureNonce   = '';
    protected $RegionId         = '';

    //
    public function __construct()
    {

        $this->AccessKeyId      = self::ACCESS_KEY_ID;
        $this->Timestamp        = $this->getTimestamp();
        $this->SignatureNonce   = $this->getSignatureNonce();

    }


    public function __set($name, $value)
    {
        if (is_null($value)) {
            return ;
        }
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } else {
            $this->$name = $value;
        }
    }

    public function __get($name)
    {
        return $this->$name;
    }


    abstract protected function request($api);



    protected function api($api, $method='GET')
    {
        $method = strtoupper($method);

        if ($method == 'POST') {
            $response = Requester::post($api, $this->getParams($method));
        } else {
            $response = Requester::get($api, $this->getParams($method));
        }

        return $this->response($response);

    }


    protected function response($response)
    {
        switch ($this->Format) {
            case 'JSON' :
                return $this->parseJsonResponse($response);
                break;
            case 'XML' :
                return $this->parseXmlResponse($response);
                break;
            default :
                return $this->parseDefaultResponse($response);
        }
    }


    private function parseJsonResponse($response)
    {
        $result = json_decode($response);
        if (json_last_error()==JSON_ERROR_NONE) {
            $result->ParseError = false;
        } else {
            $result = new stdClass();
            $result->ParseError = json_last_error_msg();
        }

        $result->Original = $response;

        return $result;

    }

    private function parseXmlResponse($response)
    {
        libxml_use_internal_errors(true);
        $result = simplexml_load_string($response);
        if ($result===false) {
            $result = new stdClass();
            $result->ParseError = libxml_get_errors();
            libxml_clear_errors();
        } else {
            $result = json_decode(json_encode($result));
            $result->ParseError = false;
        }
        $result->Original = $response;

        return $result;
    }

    private function parseDefaultResponse($response)
    {

        $result = new stdClass();
        $result->ParseError = false;
        $result->Original = $response;

        return $result;

    }





    protected function getParams($method)
    {
        $ref = new ReflectionClass($this);
        $properties = array_keys($ref->getDefaultProperties());
        $params = array();
        foreach ($properties as $property) {
            $params[$property] = $this->$property;
        }
        $params['Signature'] = $this->createSignature($params, $method);
        return $params;
    }

    private function createSignature($params, $method)
    {
        ksort($params);
        $query_string = http_build_query($params, null, '&', PHP_QUERY_RFC3986);
        $param_string = strtoupper($method).'&'.rawurlencode('/').'&'.rawurlencode($query_string);
        $sign = base64_encode(hash_hmac('sha1', $param_string, self::ACCESS_KEY_SECRET.'&', true));
        return $sign;
    }


    /**
     *
     * 按阿里云API要求 获取请求的时间戳。
     * 日期格式按照ISO8601标准表示，并需要使用UTC时间。格式为YYYY-MM-DDThh:mm:ssZ
     * 例如，2015-11-23T04:00:00Z（为北京时间2015年11月23日12点0分0秒）
     *
     */
    public function getTimestamp()
    {
        //获取当前使用时区
        $timezone = date_default_timezone_get();
        //临时设置当前使用时区为UTC
        date_default_timezone_set('UTC');
        //获取指定格式的UTC时间戳
        $Timestamp = date('Y-m-d\TH:i:s\Z');
        //还原时区
        date_default_timezone_set($timezone);

        return $Timestamp;

    }


    public function getSignatureNonce()
    {
        return md5(uniqid(mt_rand(),true)).mt_rand();
    }





}