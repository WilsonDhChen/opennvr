<?php
class DriverModel extends Model {
    private $api_url = "";

    public function __construct()
    {
        //parent::__construct();
        $this->api_url = config("api_driver")->value();
    }

    public function get($type) 
    {
        $url = $this->_parse_url($type);
        return json_decode(Requester::get($url),true);
    }

    public function update($type, $data)
    {
        $url = $this->_parse_url($type);
        return $this->_post($url, $data);
    }




    private function _parse_url($type)
    {
        return str_replace('{type}', $type, $this->api_url);
    }

    private function _post($url , $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

}
