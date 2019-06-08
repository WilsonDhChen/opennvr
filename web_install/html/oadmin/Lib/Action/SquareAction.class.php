<?php

class SquareAction extends Action {

	public function index()
	{


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
        
        foreach ($lists as &$value) {
            $value['shot_img'] = $this->getShotImg($value['sId']);
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

        $play_api_url = config("api_play_url")->value();
		$app = M()->table('config_global_keyvalue')->where("name = 'app'")->find();
        $this->play_url = str_replace(array('{id}','{host}','{app}'),array($this->info['id'],$_SERVER['HTTP_HOST'],$app['value']),$play_api_url);

        $this->display();
    }

    public function getShotImg($id)
    {
        $shot_api_url = config("snapshot_url")->value();
        $app = M()->table('config_global_keyvalue')->where("name = 'app'")->find();
        return str_replace(array('{id}','{host}','{app}'),array($id,$_SERVER['HTTP_HOST'],$app['value']),$shot_api_url);
    }

}