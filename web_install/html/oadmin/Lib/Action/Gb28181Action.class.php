<?php

class Gb28181Action extends BaseAction
{

    public function index()
    {

        $field = M()->table('config_gb28181_keyvalue')->select();
        $info = array();
        foreach ($field as $value) {
            $info[$value['name']] = $value['value'];
        }

        $this->assign("info",$info);

        $this->display();
    }

    public function update()
    {

        $error_field = '';

        foreach ($_POST as $key=>$value) {
            $result = M()->table("config_gb28181_keyvalue")->where("name = '{$key}'")->setField("value",$value);
            if ($result === false) {
                $error_field .= M()->table("config_gb28181_keyvalue")->where("name = '{$key}'")->getField("desc").'#';
            }
        }

        if ($error_field) {
            $this->response("error",$error_field.'更新失败');
        } else {
            D('Gb28181')->basechanged();
            $this->response("success",'更新成功');
        }

    }

    public function network()
    {

        $Mdl = M();
        $this->lists = $Mdl->table('config_gb28181_sips')->page();
        $this->page_html = $Mdl->page->html();
		$api_site = config("api_site")->value();
		$booterror = json_decode(Requester::get($api_site.'/gb28181-api/booterror.json'),true);
		$booterror = explode("\n",$booterror['booterror']);
		$this->booterror = $booterror;
        $this->display();
    }

    public function network_info()
    {
        $id = I('get.id',0,'intval');
        if ($id) {
            $this->info = M()->table('config_gb28181_sips')->where("id = '{$id}'")->find();
        }

        $this->ipv4 = D('Gb28181')->getEths("v4");
        $this->ipv6 = D('Gb28181')->getEths("v6");

        $this->display();
    }

    public function network_post()
    {

        $id = I('post.id',0,'intval');

        unset($_POST['id']);

        if ($id) {
            $result = M()->table('config_gb28181_sips')->where("id = '{$id}'")->save($_POST);
        } else {
            $result = M()->table('config_gb28181_sips')->add($_POST);
        }
        if ($result==false) {
            $this->response("error",'处理失败');
        } else {
            
            $this->response("success",'处理成功');
        }

    }

    public function network_delete()
    {
        $id = I('post.id',0,'intval');
        $result = M()->table('config_gb28181_sips')->where("id = '{$id}'")->delete();
        if ($result==false) {
            $this->response("error",'处理失败');
        } else {
            $this->response("success",'处理成功');
        }
    }

    public function restart()
    {
        $result = D('Gb28181')->restart();
        if ( $result['return'] == 0) {
            $this->response("success",'命令已发送');
        } else {
            $this->response("success",'网络故障，请重试！');
        }
    }

    public function parents()
    {

        $Mdl = M();
        $this->lists = $Mdl->table('config_gb28181_parents')->page();
        $this->page_html = $Mdl->page->html();
        $result = D('Gb28181')->getParentstatus();
        $status = array();
        foreach ($result['status'] as $val) {
            $status[$val['id']] = $val;
        }
        $this->assign("status",$status);
        $this->display();
    }

    public function parents_config()
    {

        $id = I('get.id',0,'intval');
        if ($id) {
            $this->info = M()->table('config_gb28181_parents')->where("id = '{$id}'")->find();
        }
        $this->factory = M()->table('ly_sys_factory')->order("id asc,sort desc")->select();
        $this->via_addr = D('Gb28181')->getEths();
        $this->display();
    }

    public function parents_config_post()
    {

        $id = I('post.id',0,'intval');
        unset($_POST['id']);
        if ($id) {
            $result = M()->table('config_gb28181_parents')->where("id = '{$id}'")->save($_POST);
        } else {
            $result = M()->table('config_gb28181_parents')->add($_POST);
        }

        if ($result!==false) {
            D('Gb28181')->parentchanged();
            $this->response("success",'处理成功');
        } else {
            $this->response("error",'网络故障，请重试！');
        }

    }

    public function delete()
    {
        $id = I('post.id',0,'intval');
        $result = M()->table('config_gb28181_parents')->where("id = '{$id}'")->delete();
        if ($result!==false) {
            D('Gb28181')->parentchanged();
            $this->response("success",'删除成功');
        } else {
            $this->response("error",'网络故障，请重试！');
        }
    }

    public function childs()
    {

        $Mdl = M();
        $this->lists = $Mdl->table('bi_gb28181_subdevs_auth')->page();
        $this->factory = M()->table('ly_sys_factory')->order("id asc,sort desc")->select();
        $this->page_html = $Mdl->page->html();

        $this->display();
    }

    public function childs_config()
    {

        $id = I('get.id',0,'intval');
        if ($id) {
            $this->info = M()->table('bi_gb28181_subdevs_auth')->where("nId = '{$id}'")->find();
        }
        $this->factory = M()->table('ly_sys_factory')->order("id asc,sort desc")->select();
        $this->display();
    }

    public function childs_config_post()
    {

        $id = I('post.id',0,'intval');
        unset($_POST['id']);
        if ($id) {
            $result = M()->table('bi_gb28181_subdevs_auth')->where("nId = '{$id}'")->save($_POST);
        } else {
            $result = M()->table('bi_gb28181_subdevs_auth')->add($_POST);
        }

        if ($result!==false) {
            Requester::get('http://127.0.0.1:180/gb28181-api/subdevchanged?devid='.$_POST['sDevId']);
            $this->response("success",'处理成功');
        } else {
            $this->response("error",'网络故障，请重试！');
        }

    }

    public function childs_delete()
    {
        $id = I('post.id',0,'intval');
        $result = M()->table('bi_gb28181_subdevs_auth')->where("nId = '{$id}'")->delete();
        if ($result!==false) {
            $this->response("success",'删除成功');
        } else {
            $this->response("error",'网络故障，请重试！');
        }
    }

    public function online()
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
        $where = "1=1";
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
        $device_name = M()->table('config_global_keyvalue')->where("name = 'mode'")->find()['value'];
        if($device_name=='nvr'){
            $this->lists = $Mdl->table('ext_gb28181_catalog')->where($where)->order("sParentChid asc")->page();
        }else{
            $this->lists = $Mdl->table('ext_gb28181_catalog')->where($where)->page();
        }
        $this->page_html = $Mdl->page->html();
        $this->device_name = $device_name;
        $this->assign("sParentChid",$sParentChid);
        $this->assign("sName",$sName);
        $this->assign("sChid",$sChid);
        $this->assign("sAreaName",$sAreaName);
        $this->assign("addrtype",$addrtype);
        $this->display();
    }



//共享页面
    public function share_data_list(){
        $id = I('id');
        if(!$id){
            $this->response("error",'缺少参数！');
        }
        $table = 'ext_pshared_'.$id;
        $exist = M()->table('ext_gb28181_catalog')->query('show tables like "'.$table.'"'); 
        if(empty($exist)){
            $sql = "CREATE TABLE `".$table."` (`nId` int(11) NOT NULL AUTO_INCREMENT,`sParentChid` varchar(40) DEFAULT '',`sChid` varchar(40) DEFAULT '',`sChidNew` varchar(40) DEFAULT '',`sNameNew` varchar(100) DEFAULT '',`sUpOrg` varchar(40) DEFAULT '',`bOrg` int(11) DEFAULT '0',PRIMARY KEY (`nId`),UNIQUE KEY `ext_pshared_key1` (`sParentChid`,`sChid`)) ENGINE=MyISAM AUTO_INCREMENT=596705 DEFAULT CHARSET=utf8;";
            M()->execute($sql);
        }
        $this->assign("ext_table",$table);
        $this->assign("id",$id);
        $this->groups = D('Gb28181')->getsubdevs();
        $this->display();
    }

    //共享列表
    public function ajax_share_data(){
        $sParentChid = I('sParentChid');
        $sChid = I('sChid');
        $f = I('f');
        $ext_table = I('ext_table');
        if($f){
            $where = "sUpOrg is null and sParentChid = {$sParentChid} and sManufacturer = ''";
        }else{
            $where = "sUpOrg = '{$sChid}' and sParentChid = '{$sParentChid}' and bOrg = 1";
        }
        $tree = M()->table('ext_gb28181_catalog')->where($where)->select();
        $sel_data = $this->get_sel_data($ext_table);
        foreach($tree as $k => $v){
            $sName = htmlspecialchars($v['sName']);
            $nId = htmlspecialchars($v['nId']);
            $add_status = '';
            //$cameras_check = M()->table("$ext_table")->where("sUpOrg is null and sChidNew = '{$v['sChid']}'")->find();
            if(isset($sel_data['cameras_check'][$v['sChid']])){
                $add_status = $add_status.",checked:true";
            }
            //$cameras_chkDisabled = M()->table("$ext_table")->where("sUpOrg is not null and sChidNew = '{$v['sChid']}'")->find();
            if(isset($sel_data['cameras_chkDisabled'][$v['sChid']])){
                $add_status = $add_status.",chkDisabled:true";
            }
            $html = $html."{ id:'".$v['sChid']."', pId:'".$v['sUpOrg']."', name:'".$sName."',sParentChid:'".$v['sParentChid']."', open:true,nid:'".$nId."',isParent:true".$add_status."},"; 
        }
        $where_cameras = "sUpOrg = '{$sChid}' and sParentChid = '{$sParentChid}' and bOrg = 0";
        $tree_cameras = M()->table('ext_gb28181_catalog')->where($where_cameras)->select();
        foreach($tree_cameras as $k=>$v){
            $sName = htmlspecialchars($v['sName']);
            $nId = htmlspecialchars($v['nId']);
            $add_status = '';
            //$cameras_check = M()->table("$ext_table")->where("sUpOrg is null and sChidNew = '{$v['sChid']}'")->find();
            if(isset($sel_data['cameras_check'][$v['sChid']])){
                $add_status = $add_status.",checked:true";
            }
            //$cameras_chkDisabled = M()->table("$ext_table")->where("sUpOrg is not null and sChidNew = '{$v['sChid']}'")->find();
            if(isset($sel_data['cameras_chkDisabled'][$v['sChid']])){
                $add_status = $add_status.",chkDisabled:true";
            }
            $html = $html."{ id:'".$v['sChid']."', pId:'".$v['sUpOrg']."', name:'".$sName."',sParentChid:'".$v['sParentChid']."', open:true,nid:'".$nId."'".$add_status."},"; 
        }
        $html = rtrim($html,',');
        echo '['.$html.']';
    }  


    //获取已选数据
    private  function get_sel_data($ext_table=''){
        $cameras_check_arr =array();
        $cameras_chkDisabled_arr =array();
        $cameras_check = M()->table("$ext_table")->where("sUpOrg is null")->field('sChidNew')->select();
        $cameras_chkDisabled = M()->table("$ext_table")->where("sUpOrg is not null")->field('sChidNew')->select();
        foreach($cameras_check as $v){
            $cameras_check_arr[] = $v['sChidNew'];
        }
        foreach($cameras_chkDisabled as $v){
            $cameras_chkDisabled_arr[] = $v['sChidNew'];
        }
        if(!empty($cameras_check_arr)){
            $cameras_check_arr = array_flip($cameras_check_arr);
        }
        if(!empty($cameras_chkDisabled_arr)){
            $cameras_chkDisabled_arr = array_flip($cameras_chkDisabled_arr);
        }
        $data['cameras_check'] = $cameras_check_arr;
        $data['cameras_chkDisabled'] = $cameras_chkDisabled_arr;
        return $data;
    } 


    //共享数据
    public function ajax_pshare_data(){
        $sParentChid = I('sParentChid');
        $sChid = I('sChid');
        $ext_table = I('ext_table');
        $f = I('f');

        if($f){
            $where = "sUpOrg is null and sParentChid = '{$sParentChid}'";
        }else{
            $where = "sUpOrg = '{$sChid}' and sParentChid = '{$sParentChid}' and bOrg = 1";
        }
        $tree = M()->table("$ext_table")->where($where)->order('bOrg desc')->select();
        foreach($tree as $k => $v){
            $sName = htmlspecialchars($v['sNameNew']);
            $nId = htmlspecialchars($v['nId']);
            if($v['bOrg']==1){
            $html = $html."{ id:'".$v['sChidNew']."', pId:'".$v['sUpOrg']."', name:'".$sName."',sParentChid:'".$v['sParentChid']."', open:true,nid:'".$nId."',isParent:true},";
            }else if($v['bOrg']==0){
                $html = $html."{ id:'".$v['sChidNew']."', pId:'".$v['sUpOrg']."', name:'".$sName."',sParentChid:'".$v['sParentChid']."', open:true,nid:'".$nId."'},"; 
            }
        }
        $where_cameras = "sUpOrg = '{$sChid}' and sParentChid = '{$sParentChid}' and bOrg = 0";
        $tree_cameras = M()->table("$ext_table")->where($where_cameras)->select();
        foreach($tree_cameras as $k=>$v){
            $sName = htmlspecialchars($v['sNameNew']);
            $nId = htmlspecialchars($v['nId']);
            $html = $html."{ id:'".$v['sChidNew']."', pId:'".$v['sUpOrg']."', name:'".$sName."',sParentChid:'".$v['sParentChid']."', open:true,nid:'".$nId."'},"; 
        }
        $html = rtrim($html,',');
        echo '['.$html.']';
    }


    //共享数据删除
    public function ajax_pshare_del(){
        $sChid = I('sChid');
        $sParentChid = I('sParentChid');

    }

        //共享数据删除
    public function ajax_pshare_del_check(){
        $sChid = I('sChid');
        $sParentChid = I('sParentChid');
        $status = I('status');
        $ext_table = I('ext_table');
        if($status){
            M()->table("$ext_table")->where("(sUpOrg like '{$sChid}%' or sChidNew = '{$sChid}') and sParentChid = '{$sParentChid}'")->delete();
        }else{
           M()->table("$ext_table")->where("sChidNew = '{$sChid}' and sParentChid = '{$sParentChid}'")->delete(); 
        }
        
    }

    //共享数据添加
    public function ajax_pshare_add(){
        $sChid = I('sChid');
        $sParentChid = I('sParentChid');
        $status = I('status');
        $ext_table = I('ext_table');
        if($status==1){
            $where = "sUpOrg like '{$sChid}%' or sChid = '{$sChid}'";
        }else{
            $where = "sChid = '{$sChid}'";
        }
        $i=0;
        if($status==1){
            $sql = "select a.sChidNew from ".$ext_table." a left join ext_gb28181_catalog b on a.sChidNew = b.sChid where (b.sUpOrg like '{$sChid}%' or b.sChid = '$sChid') and a.sParentChid = {$sParentChid} and a.sUpOrg is null";
            $del_list = M()->query($sql);
            foreach($del_list as $k1 => $v1){
                M()->table($ext_table)->where("(sUpOrg like '{$sChid}%' or sChidNew = {$v1['sChidNew']}) and sParentChid = {$sParentChid}")->delete();
            }
            $i=1;
        }else{
            $sql = "select a.sChidNew from ".$ext_table." a left join ext_gb28181_catalog b on a.sChidNew = b.sChid where  a.sChidNew = '{$sChid}' and a.sParentChid = '{$sParentChid}' and a.sUpOrg is null";
            $del_list = M()->query($sql);
            foreach($del_list as $k1 => $v1){
                M()->table("$ext_table")->where(" sChidNew = {$v1['sChidNew']} and sParentChid = {$sParentChid}")->delete();
            }
            $i=1;
        }
        if($i==1){
            $data = M()->table('ext_gb28181_catalog')->where($where)->select();
            foreach($data as $k=>$v){
                $data_list['sParentChid'] = $v['sParentChid'];
                $data_list['sChid'] = $v['sChid'];
                $data_list['sChidNew'] = $v['sChid'];
                $data_list['sNameNew'] = $v['sName'];
                $data_list['bOrg'] = $v['bOrg'];
                if($v['sChid']==$sChid){
                    $data_list['sUpOrg'] = null;
                }else{
                    $data_list['sUpOrg'] = $v['sUpOrg'];
                }
                $lists[] = $data_list;
            }
            M()->table("$ext_table")->addAll($lists);
        }
    }
}
