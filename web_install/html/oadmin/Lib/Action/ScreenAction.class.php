<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/6
 * Time: 18:19
 */
class ScreenAction extends BaseAction{
    public function index(){
        $Mdl = M();
        $lists = $Mdl->table('bi_groups as a')->join("(select count(*) as total,nGroupId from bi_live_streams group by nGroupId) as b on a.nId = b.nGroupId")->where()->select();
        $this -> assign("lists", $lists);

        // 获取所有下级视频信息
        $streams = M()->table('bi_live_streams') -> select();
        $this -> assign('streams', $streams);
        //p($streams);

        $result = D('Live')->select();
        $lives = array();
        foreach ($result['streams'] as $value) {
            $lives[$value['sysid']] = $value['started'];
        }
        $this->assign("lives",$lives);
        $this->display();
        /*        // 获取分组信息
        $Mdl = M();
        $lists = $Mdl->table('bi_groups as a')->join("(select count(*) as total,nGroupId from bi_live_streams group by nGroupId) as b on a.nId = b.nGroupId")->where()->select();
        $this -> assign("lists", $lists);
        //p($lists);

        // 获取所有下级视频信息
        $streams = M()->table('bi_live_streams') -> select();
        $this -> assign('streams', $streams);
        //p($streams);

        $result = D('Live')->select();
        $lives = array();
        foreach ($result['streams'] as $value) {
            $lives[$value['sysid']] = $value['started'];
        }

        $this->assign("lives",$lives);
        $this->display();*/
    }


    public function detail()
    {

        $Live = D('Live');
        $this->liveStreamTypes = $Live->liveStreamTypes();

        $gid = I('get.gid',0,'intval');
        $id = I('get.id',0,'intval');
        if (!$id) {
            $id = M()->table("bi_live_streams")->where("nGroupId = '{$gid}'")->getField("min(nId)");
        }

        $this->info = $Live->detail_by_sysid($id);

        $play_api_url = config("api_play_url")->value();

		$app = M()->table('config_global_keyvalue')->where("name = 'app'")->find();
        $this->play_url = str_replace(array('{id}','{host}','{app}'),array($this->info['id'],$_SERVER['HTTP_HOST'],$app['value']),$play_api_url);

        /*if ($play_api_url) {
            $this->play_url = str_replace("{id}",$this->info['id'],$play_api_url);
        } else {
            $this->play_url = "rtmp://".$_SERVER['SERVER_ADDR']."/live/".$this->info['id'];
        }*/



        $this->display();
    }

    public function ptz_control()
    {

        $result = D('Live')->ptz($_POST);
        if ($result['return'] == 0) {
            $this->response("success","命令发送成功");
        } else {
            $this->response("error",$result['error']);
        }
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