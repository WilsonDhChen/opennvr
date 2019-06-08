<?php

class NetworkModel extends Model
{

    public function apiUrl($name)
    {
        $api_site = config("api_site")->value();
        $api_camera = config("api_network")->attrs();
        return $api_site.$api_camera[$name];
    }

    public function lists()
    {
        $result =  json_decode(Requester::get($this->apiUrl('lists')),true);
        if ($result['return']==0) {
            return $result['devices'];
        }else{
            return array();
        }
    }


    public function update($data)
    {
        return json_decode(Requester::json($this->apiUrl('update'),$data),true);
    }

    public function restart($data)
    {
        return json_decode(Requester::json($this->apiUrl('action'),$data),true);
    }




}