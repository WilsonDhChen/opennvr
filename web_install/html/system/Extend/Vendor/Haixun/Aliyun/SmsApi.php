<?php


class SmsApi extends Aliyun
{
    const API = 'http://sms.aliyuncs.com';


    protected $Action        = 'SingleSendSms';
    protected $SignName      = '';
    protected $TemplateCode  = '';
    protected $RecNum        = '';
    protected $ParamString   = '{}';



    public function __construct($sign_name = null)
    {
        parent::__construct();

        if (!is_null($sign_name)) {
            $this->SignName = $sign_name;
        }
    }

    /**
     * 发送一条模版短信
     * $TemplateCode 短信模版code
     * $RecNum 接收人手机号码
     */
    public function sendSingle($phone_num = null, $variables=null, $smst_code = null)
    {

        if (!is_null($phone_num)) {
            $this->RecNum = $phone_num;
        }

        if (!is_null($variables)) {
            $this->setParamString($variables);
        }

        if (!is_null($smst_code)) {
            $this->TemplateCode = $smst_code;
        }

        $response =  $this->request(self::API);
        if (property_exists($response, 'Model')) {
            $response->Result = true;
        } else {
            $response->Result = false;
        }

        return $response;
    }


    //发送[用户通用]验证码
    public function sendVerificationCode($phone_num = null, $length=6)
    {
        //当前短信应用使用的短信模版code
        $this->TemplateCode = 'SMS_47410285';

        //生成验证码
        $verification_code = $this->getRandNum($length);
        $variables = array('code'=>$verification_code);
        //发送验证码
        $response = $this->sendSingle($phone_num, $variables);
        //如果发送成功 把真实验证码存入返回的response对象
        if($response->Result) {
            $response->VerificationCode = $verification_code;
        }

        return $response;

    }


    //发送[用户注册]验证码
    public function sendSignUp($phone_num = null, $variables=null)
    {

    }

    //发送[用户登录]验证码
    public function sendSignIn($phone_num = null, $variables=null)
    {

    }

    //发送[用户身份验证]验证码
    public function sendAuth($phone_num = null, $variables=null)
    {

    }

    //发送[用户信息变更]验证码
    public function sendAlter($phone_num = null, $variables=null)
    {


    }



    protected function request($api)
    {
        return $this->api($api);
    }


    public function setParamString($value)
    {

        if (is_string($value)) {
            $this->ParamString = $value;
        } else {
            $this->ParamString = json_encode($value);
        }

        return $this;
    }

    /**
     * 获取指定长度的随机数字
     *
     */
    protected function getRandNum($length=6)
    {
        return (string) mt_rand(pow(10,$length-1), pow(10,$length)-1);
    }

}