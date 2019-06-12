<?php

class CameraAction extends BaseAction
{

    public function index()
    {


        $Mdl = M();
        $this->lists = $Mdl->table('bi_groups as a')->join("(select count(*) as total,nGroupId from bi_live_streams group by nGroupId) as b on a.nId = b.nGroupId")->where()->select();
        $result = D('Live')->select();
        
        $lives = array();
        foreach ($result['streams'] as $value) {
            $lives[$value['sysid']] = array("started"=>$value['started'],"error"=>$value['error']);
        }
		
        $this->nogroupnum =  M()->table("bi_live_streams")->where("nGroupId = 0")->count();
		
        $this->assign("lives",$lives);
        $this->display();
    }

    public function detail()
    {

        $Live = D('Live');
        $this->liveStreamTypes = $Live->liveStreamTypes();

        $this->camera_dpi = config("camera_dpi")->nodes();
        $this->camera_fps = config("camera_fps")->nodes();
        $this->camera_aspect = config("camera_aspect")->nodes();
        $this->camera_vprofile = config("camera_vprofile")->nodes();
        $this->camera_videobitratetype = config("camera_videobitratetype")->nodes();
        $this->camera_audiosamplerate = config("camera_audiosamplerate")->nodes();
        $this->camera_audiochannels = config("camera_audiochannels")->nodes();
        $this->groups = M()->table('bi_groups')->where()->select();
        $this->eths   = $Live->getEths();
        $this->udprecveths = $Live->udprecveths();

        $gid = I('get.gid',0,'intval');
        $id = I('get.id',0,'intval');
        if ($id) {
            $this->info = $Live->detail_by_sysid($id);
            $gid = $this->info['groupid'];
        }

		$this->device_name = M()->table('config_global_keyvalue')->where("name = 'mode'")->find()['value'];
        $this->assign("gid",$gid);
        $this->display();
    }

    public function save()
    {

        unset($_POST['plan_time_start_h'],$_POST['plan_time_start_m'],$_POST['plan_time_over_h'],$_POST['plan_time_over_m']);

        if (!spower("update_id")) {
            unset($_POST['id']);
        }
        $Live = D('Live');
        if ($_POST['sysid']) {
            $success_tips = "更新成功";
            $result = $Live->update($_POST);
        }else{
            $success_tips = "添加成功";
            $result = $Live->add($_POST);
        }
        if ($result['return'] == 0) {
            $this->response("success",$success_tips);
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

    public function delete()
    {
        $id = I('post.id',0,'intval');
        $Live = D('Live');
        $result = $Live->delete($id);
        if ($result['return'] == 0) {
            $this->response("success","删除成功");
        } else {
            $this->response("error",$result['error']);
        }
    }

    public function camera_action(){
        $id = I('post.id',0,'intval');
        $Live = D('Live');
        $data['sysid'] = (string) $id;
        $data['action'] = I('post.status');

        $result = $Live->liveaction($data);
        if ($result['return'] == 0) {
            $this->response("success","命令发送成功！");
        } else {
            $this->response("error",$result['error']);
        }
    }

    public function get_scan()
    {

        $Live = D('Live');
        $this->info = $Live->getScan($_POST);

        $this->display();
    }

    public function get_allchsbytree()
    {
        
        $this->groups = D('Gb28181')->getsubdevs();
        $this->addrs = D('Gb28181')->address();

        $Mdl = M();
        $sParentChid = I('get.sParentChid');
        $sChid = I('get.sChid');
        $sName = I('get.sName');
        $addrtype = I('get.addrtype',0,'intval');
        if ($addrtype == 1) {
            $sAreaName = I('get.sAreaNameInput');
        } else {
            $sAreaName = I('get.sAreaNameSelect');
        }
        $where = " sChid not in (select sGB28181InputId from bi_live_streams) and sType='IPC'";
        if ($sParentChid) {
            $where .= " and sParentChid = '{$sParentChid}'";
        }
        if ($sChid) {
            $where .= " and sChid like '%{$sChid}%'";
        }
        if ($sName) {
            $where .= " and sName like '%{$sName}%'";
        }
        if ($sAreaName) {
            $where .= " and sAreaName like '%{$sAreaName}%'";
        }
        $this->lists = $Mdl->table('ext_gb28181_catalog')->where($where)->page();
        $this->page_html = $Mdl->page->html();
        $this->assign("sParentChid",$sParentChid);
        $this->assign("sName",$sName);
        $this->assign("sChid",$sChid);
        $this->assign("sAreaName",$sAreaName);
        $this->assign("addrtype",$addrtype);
        $this->display();

    }

    public function getinfo()
    {
        $id = I('post.id',0,'intval');
        $result = D('Live')->status_by_sysid($id);

        $this->response("success",'',$result['started']?$result['started']:0);
    }
}
