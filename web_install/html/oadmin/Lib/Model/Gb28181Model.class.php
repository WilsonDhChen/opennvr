<?php

class Gb28181Model extends Model
{

    public function apiUrl($name)
    {
        $api_site = config("api_site")->value();
        $api_camera = config("api_gb28181")->attrs();
        return $api_site.$api_camera[$name];
    }

    public function getEths($type)
    {
        $url = $this->apiUrl('eths');
        if ($type) {
            $url .= "?type=".$type;
        }
        return json_decode(Requester::get($url),true);
    }

    public function getParentstatus()
    {
        return json_decode(Requester::get($this->apiUrl('parentstatus')),true);
    }

    public function getsubdevs()
    {
        return json_decode(Requester::get($this->apiUrl('getsubdevs')),true);
    }
    public function address()
    {
        return json_decode(Requester::get($this->apiUrl('address')),true);
    }

    public function parentchanged()
    {
        return json_decode(Requester::post($this->apiUrl('parentchanged')),true);
    }

    public function basechanged()
    {
        return json_decode(Requester::post($this->apiUrl('basechanged')),true);
    }

    public function restart()
    {
        $data['cmd'] = "restart";
        return json_decode(Requester::json($this->apiUrl('cmd'),$data),true);
    }


}
