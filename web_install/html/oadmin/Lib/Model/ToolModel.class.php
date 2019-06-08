<?php

class ToolModel extends Model
{

    public function apiUrl($name)
    {
        $api_site = config("api_site")->value();
        $api_camera = config("system_tool")->attrs();
        return $api_site.$api_camera[$name];
    }

    public function mediasrvstatus()
    {
        return json_decode(Requester::get($this->apiUrl('mediasrvstatus')),true);
    }

    public function resorces()
    {
        return json_decode(Requester::get($this->apiUrl('resources')),true);
    }

    public function system_action($data)
    {
        return json_decode(Requester::json($this->apiUrl('system_action'),$data),true);
    }

}
