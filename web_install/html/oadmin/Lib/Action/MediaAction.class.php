<?php

class MediaAction extends BaseAction
{

    public function index()
    {

        $field = M()->table('config_mediasrv_keyvalue')->select();
        $info = array();
        foreach ($field as $value) {
            $info[$value['name']] = $value['value'];
        }

        $this->assign("info",$info);
        $field1 = M()->table('config_mediasrv_global_keyvalue')->select();
        $info1 = array();
        foreach ($field1 as $value) {
            $info1[$value['name']] = $value['value'];
        }

        $this->assign("info1",$info1);
        $this->display();
    }

    public function update()
    {

        $error_field = '';

        foreach ($_POST as $key=>$value) {
            $result = M()->table("config_mediasrv_keyvalue")->where("name = '{$key}'")->setField("value",$value);
            if ($result === false) {
                $error_field .= M()->table("config_mediasrv_keyvalue")->where("name = '{$key}'")->getField("desc").'#';
            }
        }

        if ($error_field) {
            $this->response("error",$error_field.'更新失败');
        } else {
            $api_site = config("api_site")->value();
			$ets = json_decode(Requester::get($api_site.'/mediasrv-api/settingchanged?type=base'),true);
            $this->response("success",'更新成功');
        }

    }

    public function ets()
    {

        $Mdl = M();
        $this->lists = $Mdl->table('config_mediasrv_ets')->page();
        $this->page_html = $Mdl->page->html();
		$this->ipv4 = D('Gb28181')->getEths("v4");
        $this->ipv6 = D('Gb28181')->getEths("v6");
        $this->display();
    }

    public function ets_info()
    {
        $id = I('get.id',0,'intval');
        if ($id) {
            $this->info = M()->table('config_mediasrv_ets')->where("id = '{$id}'")->find();
        }
		$this->ipv4 = D('Gb28181')->getEths("v4");
        $this->ipv6 = D('Gb28181')->getEths("v6");
        $this->display();
    }

    public function ets_post()
    {

        $id = I('post.id',0,'intval');

        unset($_POST['id']);
        if ($id) {
            $result = M()->table('config_mediasrv_ets')->where("id = '{$id}'")->save($_POST);
        } else {
            $result = M()->table('config_mediasrv_ets')->add($_POST);
        }
        if ($result==false) {
            $this->response("error",'处理失败');
        } else {
            $api_site = config("api_site")->value();
			$ets = json_decode(Requester::get($api_site.'/mediasrv-api/settingchanged?type=net-vts'),true);
            $this->response("success",'处理成功');
        }

    }

    public function ets_delete()
    {
        $id = I('post.id',0,'intval');
        $result = M()->table('config_mediasrv_ets')->where("id = '{$id}'")->delete();
        if ($result==false) {
            $this->response("error",'处理失败');
        } else {
			$api_site = config("api_site")->value();
			$ets = json_decode(Requester::get($api_site.'/mediasrv-api/settingchanged?type=net-vts'),true);
            $this->response("success",'处理成功');
        }
    }


    public function rtsp()
    {

        $Mdl = M();
        $this->lists = $Mdl->table('config_mediasrv_rtsp')->page();
        $this->page_html = $Mdl->page->html();
		$this->ipv4 = D('Gb28181')->getEths("v4");
        $this->ipv6 = D('Gb28181')->getEths("v6");
        $this->display();
    }

    public function rtsp_info()
    {
        $id = I('get.id',0,'intval');
        if ($id) {
            $this->info = M()->table('config_mediasrv_rtsp')->where("id = '{$id}'")->find();
        }
		$this->ipv4 = D('Gb28181')->getEths("v4");
        $this->ipv6 = D('Gb28181')->getEths("v6");
        $this->display();
    }

    public function rtsp_post()
    {

        $id = I('post.id',0,'intval');
        unset($_POST['id']);
        if ($id) {
            $result = M()->table('config_mediasrv_rtsp')->where("id = '{$id}'")->save($_POST);
        } else {
            $result = M()->table('config_mediasrv_rtsp')->add($_POST);
        }
        if ($result==false) {
            $this->response("error",'处理失败');
        } else {
            $api_site = config("api_site")->value();
			$ets = json_decode(Requester::get($api_site.'/mediasrv-api/settingchanged?type=net-rtsp'),true);
            $this->response("success",'处理成功');
        }

    }

    public function rtsp_delete()
    {
        $id = I('post.id',0,'intval');
        $result = M()->table('config_mediasrv_rtsp')->where("id = '{$id}'")->delete();
        if ($result==false) {
            $this->response("error",'处理失败');
        } else {
			$api_site = config("api_site")->value();
			$ets = json_decode(Requester::get($api_site.'/mediasrv-api/settingchanged?type=net-rtsp'),true);
            $this->response("success",'处理成功');
        }
    }



	    public function rtmp()
    {

        $Mdl = M();
        $this->lists = $Mdl->table('config_mediasrv_rtmp')->page();
        $this->page_html = $Mdl->page->html();
		$this->ipv4 = D('Gb28181')->getEths("v4");
        $this->ipv6 = D('Gb28181')->getEths("v6");
        $this->display();
    }

    public function rtmp_info()
    {
        $id = I('get.id',0,'intval');
        if ($id) {
            $this->info = M()->table('config_mediasrv_rtmp')->where("id = '{$id}'")->find();
        }
		$this->ipv4 = D('Gb28181')->getEths("v4");
        $this->ipv6 = D('Gb28181')->getEths("v6");
        $this->display();
    }

    public function rtmp_post()
    {

        $id = I('post.id',0,'intval');

        unset($_POST['id']);
        if ($id) {
            $result = M()->table('config_mediasrv_rtmp')->where("id = '{$id}'")->save($_POST);
        } else {
            $result = M()->table('config_mediasrv_rtmp')->add($_POST);
        }
        if ($result==false) {
            $this->response("error",'处理失败');
        } else {
            $api_site = config("api_site")->value();
			$ets = json_decode(Requester::get($api_site.'/mediasrv-api/settingchanged?type=net-rtmp'),true);
            $this->response("success",'处理成功');
        }

    }

    public function rtmp_delete()
    {
        $id = I('post.id',0,'intval');
        $result = M()->table('config_mediasrv_rtmp')->where("id = '{$id}'")->delete();
        if ($result==false) {
            $this->response("error",'处理失败');
        } else {
			$api_site = config("api_site")->value();
			$ets = json_decode(Requester::get($api_site.'/mediasrv-api/settingchanged?type=net-rtmp'),true);
            $this->response("success",'处理成功');
        }
    }



	    public function http()
    {

        $Mdl = M();
        $this->lists = $Mdl->table('config_mediasrv_httpts')->page();
        $this->page_html = $Mdl->page->html();
		$this->ipv4 = D('Gb28181')->getEths("v4");
        $this->ipv6 = D('Gb28181')->getEths("v6");
        $this->display();
    }

    public function http_info()
    {
        $id = I('get.id',0,'intval');
        if ($id) {
            $this->info = M()->table('config_mediasrv_httpts')->where("id = '{$id}'")->find();
        }
		$this->ipv4 = D('Gb28181')->getEths("v4");
        $this->ipv6 = D('Gb28181')->getEths("v6");
        $this->display();
    }

    public function http_post()
    {

        $id = I('post.id',0,'intval');

        unset($_POST['id']);
        if ($id) {
            $result = M()->table('config_mediasrv_httpts')->where("id = '{$id}'")->save($_POST);
        } else {
            $result = M()->table('config_mediasrv_httpts')->add($_POST);
        }
        if ($result==false) {
            $this->response("error",'处理失败');
        } else {
            $api_site = config("api_site")->value();
			$ets = json_decode(Requester::get($api_site.'/mediasrv-api/settingchanged?type=net-httpts'),true);
            $this->response("success",'处理成功');
        }

    }

    public function http_delete()
    {
        $id = I('post.id',0,'intval');
        $result = M()->table('config_mediasrv_httpts')->where("id = '{$id}'")->delete();
        if ($result==false) {
            $this->response("error",'处理失败');
        } else {
			$api_site = config("api_site")->value();
			$ets = json_decode(Requester::get($api_site.'/mediasrv-api/settingchanged?type=net-httpts'),true);
            $this->response("success",'处理成功');
        }
    }



	    public function hls()
    {

        $Mdl = M();
        $field = M()->table('config_mediasrv_global_keyvalue')->select();
        $info = array();
        foreach ($field as $value) {
            $info[$value['name']] = $value['value'];
        }

        $this->assign("info",$info);
        $this->lists = $Mdl->table('config_mediasrv_httpdhls')->page();
        $this->page_html = $Mdl->page->html();
		$this->ipv4 = D('Gb28181')->getEths("v4");
        $this->ipv6 = D('Gb28181')->getEths("v6");
        $this->display();
    }

    public function hls_info()
    {
        $id = I('get.id',0,'intval');
        if ($id) {
            $this->info = M()->table('config_mediasrv_httpdhls')->where("id = '{$id}'")->find();
        }
		$this->ipv4 = D('Gb28181')->getEths("v4");
        $this->ipv6 = D('Gb28181')->getEths("v6");
        $this->display();
    }

    public function hls_post()
    {

        $id = I('post.id',0,'intval');

        unset($_POST['id']);
        if ($id) {
            $result = M()->table('config_mediasrv_httpdhls')->where("id = '{$id}'")->save($_POST);
        } else {
            $result = M()->table('config_mediasrv_httpdhls')->add($_POST);
        }
        if ($result==false) {
            $this->response("error",'处理失败');
        } else {
            $api_site = config("api_site")->value();
			$ets = json_decode(Requester::get($api_site.'/mediasrv-api/settingchanged?type=net-hls'),true);
            $this->response("success",'处理成功');
        }

    }

    public function hls_delete()
    {
        $id = I('post.id',0,'intval');
        $result = M()->table('config_mediasrv_httpdhls')->where("id = '{$id}'")->delete();
        if ($result==false) {
            $this->response("error",'处理失败');
        } else {
			$api_site = config("api_site")->value();
			$ets = json_decode(Requester::get($api_site.'/mediasrv-api/settingchanged?type=net-hls'),true);
            $this->response("success",'处理成功');
        }
    }


    public function record()
    {

        $field = M()->table('config_mediasrv_global_keyvalue')->select();
        $info = array();
        foreach ($field as $value) {
            $info[$value['name']] = $value['value'];
        }

        $this->assign("info",$info);

        $this->display();
    }

    public function record_update()
    {

        $error_field = '';

        foreach ($_POST as $key=>$value) {
            $result = M()->table("config_mediasrv_global_keyvalue")->where("name = '{$key}'")->setField("value",$value);
            if ($result === false) {
                $error_field .= M()->table("config_mediasrv_global_keyvalue")->where("name = '{$key}'")->getField("desc").'#';
            }
        }

        if ($error_field) {
            $this->response("error",$error_field.'更新失败');
        } else {
            $api_site = config("api_site")->value();
			$ets = json_decode(Requester::get($api_site.'/mediasrv-api/settingchanged?type=record'),true);
            $this->response("success",'更新成功');
        }

    }

    public function hlsconfigure()
    {

        $field = M()->table('config_mediasrv_global_keyvalue')->select();
        $info = array();
        foreach ($field as $value) {
            $info[$value['name']] = $value['value'];
        }

        $this->assign("info",$info);

        $this->display();
    }

    public function hlsconfigure_update()
    {

        $error_field = '';

        foreach ($_POST as $key=>$value) {
            $result = M()->table("config_mediasrv_global_keyvalue")->where("name = '{$key}'")->setField("value",$value);
            if ($result === false) {
                $error_field .= M()->table("config_mediasrv_global_keyvalue")->where("name = '{$key}'")->getField("desc").'#';
            }
        }

        if ($error_field) {
            $this->response("error",$error_field.'更新失败');
        } else {
            $api_site = config("api_site")->value();
			$ets = json_decode(Requester::get($api_site.'/mediasrv-api/settingchanged?type=hls'),true);
            $this->response("success",'更新成功');
        }

    }
	    public function gb28181()
    {	
        $field = M()->table('config_mediasrv_global_keyvalue')->select();
        $info = array();
        foreach ($field as $value) {
            $info[$value['name']] = $value['value'];
        }

        $this->assign("info",$info);

        $this->display();
    }

    public function gb28181_update()
    {

        $error_field = '';

        foreach ($_POST as $key=>$value) {
            $result = M()->table("config_mediasrv_global_keyvalue")->where("name = '{$key}'")->setField("value",$value);
            if ($result === false) {
                $error_field .= M()->table("config_mediasrv_global_keyvalue")->where("name = '{$key}'")->getField("desc").'#';
            }
        }

        if ($error_field) {
            $this->response("error",$error_field.'更新失败');
        } else {
            $api_site = config("api_site")->value();
			$ets = json_decode(Requester::get($api_site.'/mediasrv-api/settingchanged?type=gb28181'),true);
            $this->response("success",'更新成功');
        }

    }
}
