<?php

class LiveModel extends Model
{

    public function apiUrl($name)
    {
        $api_site = config("api_site")->value();
        $api_camera = config("api_camera")->attrs();
        return $api_site.$api_camera[$name];
    }

    public function select()
    {
        return json_decode(Requester::get($this->apiUrl('streams')),true);
    }

    public function add($data)
    {
        return json_decode(Requester::json($this->apiUrl('add'),$data),true);
    }

    public function update($data)
    {
        return json_decode(Requester::json($this->apiUrl('update'),$data),true);
    }

    public function detail_by_sysid($sysid)
    {
        $api_url = str_replace("{sysid}",$sysid,$this->apiUrl('detail_by_sysid'));
        $result = json_decode(Requester::get($api_url),true);
        return $result;
    }

    public function status_by_sysid($sysid)
    {
        $api_url = str_replace("{sysid}",$sysid,$this->apiUrl('status_by_sysid'));
        $result = json_decode(Requester::get($api_url),true);
        return $result;
    }

    public function liveStreamTypes()
    {
        $result = json_decode(Requester::get($this->apiUrl('livestreamtypes')),true);
        return $result['types'];
    }

    public function delete($id)
    {
        $data = array("sysid"=> (string) $id);
        return json_decode(Requester::json($this->apiUrl('delete'),$data),true);
    }

    public function delete_group($id)
    {
        $data = array("groupid"=> (string) $id);
        return json_decode(Requester::json($this->apiUrl('group_delete'),$data),true);
    }

    public function ptz($data)
    {
        return json_decode(Requester::json($this->apiUrl('ptz'),$data),true);
    }
    public function liveaction($data)
    {
        return json_decode(Requester::json($this->apiUrl('action'),$data),true);
    }

    public function getScan($data)
    {
        return json_decode(Requester::json($this->apiUrl('scan'),$data),true);
    }

    public function getAllchsbytree()
    {
        return json_decode(Requester::get($this->apiUrl('getallchsbytree')),true);
    }

    public function getEths()
    {
        return json_decode(Requester::get($this->apiUrl('eths')),true);
    }

    public function udprecveths()
    {
        return json_decode(Requester::get($this->apiUrl('udprecveths')),true);
    }
}
