<?php

class IndexAction extends Action {

	public function index()
	{
        $square_status = config('square_status')->value();
        if ($square_status==0) {
            redirect("/Desktop");
        }

        //redirect(__APP__.'/Login');die; // 2017-9-5 Ryan

        $result = D('Live')->select();
        $live  = array();
        foreach ($result['streams'] as $value) {
            $live[$value['sysid']] = $value['started'];
        }

        $where = "bPublish=1";
        $groupid = I('get.groupid',0,'intval');
        if ($groupid) {
            $where .= " and nGroupId = '{$groupid}'";
        }

        $keywords = I('get.keywords','','input_filter');
        if ($keywords) {
            $where .= " and sName like '%{$keywords}%'";
        }
        $this->groups = M()->table('bi_groups')->select();

        $lists = M()->table('bi_live_streams')->where($where)->order("sName asc")->page();
        $this->page_html = M()->page->html();
        
        $config_shot = config("snapshot_url")->value();
        $config_flv = config("api_play_url")->value();
        
        $domain = M()->table('config_global_keyvalue')->where("name='domain'")->find();
        $domain = $domain['value'];

        foreach ($lists as &$value) {
            $value['shot_img'] = $this->getShotImg($value['sId'], $config_shot, $domain);
            //$value['flv_url'] = $this->getVideoUrl($value['sId'], $config_flv);
        }
        unset($value);
        
        $this->assign('live',$live);
        $this->assign('lists',$lists);
        $this->assign('groupid',$groupid);
        $this->assign('keywords',$keywords);
		$this->display();
	}

    public function detail()
    {
        $id = I('get.id');
        if (!$id) {
            $this->response("error","参数丢失");
        }

        /*
        $play_api_url = config("api_play_url")->value();
        $api_play_url_m3u8 = config("api_play_url_m3u8")->value();
		$app = M()->table('config_global_keyvalue')->where("name = 'app'")->find();
        $this->play_url = str_replace(array('{id}','{host}','{app}'),array($id,$_SERVER['HTTP_HOST'],$app['value']),$play_api_url);
        $this->m3u8_play_url =str_replace(array('{id}','{host}','{app}'),array($id,$_SERVER['HTTP_HOST'],$app['value']),$api_play_url_m3u8);
         */
        $url = $this->getVideoUrl($id);
        $this->play_url = $url;
        $this->display();
    }

    private function getShotImg($id, $shot_api_url=null, $domain=null)
    {
        if (empty($shot_api_url))
            $shot_api_url = config("snapshot_url")->value();

		$app = M()->table('config_global_keyvalue')->where("name = 'app'")->find();
        $host = $this->getHost();
        $url = str_replace(array('{id}','{host}','{app}'),array($id,$host,$app['value']),$shot_api_url);

        $suffix = explode('?', $url);
        $param = array();
        if (!empty($suffix[1])) 
            parse_str($suffix[1], $param);

        if (empty($domain)) {
            $domain = M()->table('config_global_keyvalue')->where("name='domain'")->find();
            $domain = $domain['value'];
        }
        $param['domain'] = $domain;
        
        $url = $suffix[0].'?'.urldecode(http_build_query($param));
        return $url;
    }

    private function getVideoUrl($id, $api_url=null)
    {
        if (empty($api_url)) 
            $api_url = config("api_play_url")->value();

        /*
		$app = M()->table('config_global_keyvalue')->where("name = 'app'")->find();
        $host = $this->getHost();
        return str_replace(array('{id}','{host}','{app}'),array($id,$host,$app['value']),$api_url);
         */
        $mod = D('Stream');
        $url = $mod->get_flv($id, $api_url);
        return $url;
    }

    private function getHost()
    {
        $host = $_SERVER['HTTP_HOST'];
        $host = explode(':', $host);
        $host = array_shift($host);
        return $host;
    }

}
