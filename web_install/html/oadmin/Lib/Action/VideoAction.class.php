<?php

class VideoAction extends BaseAction
{

    public function index()
    {

        $Mdl = M();
        $this->lists = $Mdl->table('bi_groups as a')->join("(select count(*) as total,nGroupId from bi_live_streams group by nGroupId) as b on a.nId = b.nGroupId")->where()->select();

        $this->nogroupnum =  M()->table("bi_live_streams")->where("nGroupId = 0")->count();

        $this->display();
    }

    public function detail()
    {

        $Live = D('Live');

        $gid = I('get.gid',0,'intval');
        $id = I('get.id',0,'intval');
        if (!$id) {
            $id = M()->table("bi_live_streams")->where("nGroupId = '{$gid}'")->getField("min(nId)");
        }

        $this->info = $Live->detail_by_sysid($id);

        $play_api_url = config("api_play_url")->value();
        $app = M()->table('config_global_keyvalue')->where("name = 'app'")->find();
        $this->play_url = str_replace(array('{id}','{host}','{app}'),array($this->info['id'],$_SERVER['HTTP_HOST'],$app['value']),$play_api_url);



        $this->display();
    }

    public function lists()
    {

        $gid = I('get.gid',0,'intval');
        $lists = M()->table("bi_live_streams")->field("nId,sName")->where("nGroupId = '{$gid}'")->order("nId asc")->select();

        if ($lists) {
            $this->response("success","获取成功",$lists);
        } else {
            $this->response("error","暂无数据");
        }
    }


}