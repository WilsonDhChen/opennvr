<?php

class SectionAction extends BaseAction{
	

	public function index(){
	
		$this->display();
	}
	
	public function load(){
		
		$id = I('post.id',0,'int');
		$M = M('sys_navi');
		//是否超级管理员身份
		if(D('Navi')->superAdminRole($this->staff['roles'])){
			$list = $M->query("select navi_id as id,navi_name as name,if((select count(1) from __PREFIX__sys_navi where parent_id=a.navi_id )>0,'true','false') as isParent from __PREFIX__sys_navi as a where parent_id={$id}");
		}else{
			$list = $M->query("select navi_id as id,navi_name as name,if((select count(1) from __PREFIX__sys_navi where parent_id=a.navi_id )>0,'true','false') as isParent from __PREFIX__sys_navi as a where parent_id={$id} and a.navi_id in (select distinct navi_id from __PREFIX__sys_role_access where role_id in(".implode(',',$this->staff['roles'])."))");						
		}
		
		exit(json_encode($list));
	}
	
	public function get_navi(){
		
		$navi_id = I('post.navi_id',0,'int');
		if(empty($navi_id)){
			$this->response('error','参数丢失');	
		}
		
		$M = M('sys_navi');
		$result = $M->alias('a')->field("a.*,if(a.parent_id=0,'顶级栏目',(select navi_name from ".__PREFIX__."sys_navi where navi_id=a.parent_id)) as parent_name")->where("navi_id={$navi_id}")->find();
		if($result){
			$this->response('success','获取成功',$result);	
		}else{
			$this->response('error','网络故障 请重试');	
		}
			
	}
	
	public function navi_add_post(){
		$data = array();
		$data['parent_id'] = I('post.parent_id',0,'int'); 
		$data['navi_name'] = I('post.navi_name','','html_escape'); 
		if(empty($data['navi_name'])){
			
			$this->response('error','栏目名称不能为空');
		}
		$data['module'] = I('post.module','','trim,html_filter'); 
		$data['action'] = I('post.action','','trim,html_filter'); 
		if(!empty($data['action'])){
			$data['action'] = preg_replace('/\s+/i','',$data['action']);
			$data['action'] = str_replace('，',',',$data['action']);
		}
		
		$data['conditions'] = I('post.conditions','','trim'); 
		$data['get_params'] = I('post.get_params','','trim'); 
		$data['sort'] 		= I('post.sort',0,'int');
		$data['valid_status'] = I('post.valid_status',0,'int');
		$data['insert_time'] = time();
		
		$M = M('sys_navi');
		$result = $M->add($data);	

		if($result){
			$this->response('success','操作成功');
		}else{
			$this->response('error','网络故障，操作失败');	
		}
		
	}
	
	public function navi_edit_post(){
		
		$navi_id = I('post.navi_id',0,'int');
		
		if(empty($navi_id)){
			
			$this->response('error','参数丢失');
		}		
		
		$data = array();
		$data['parent_id'] = I('post.parent_id',0,'int'); 
		$data['navi_name'] = I('post.navi_name','','html_escape'); 
		if(empty($data['navi_name'])){
			
			$this->response('error','栏目名称不能为空');
		}
		$data['module'] = I('post.module','','trim,html_filter'); 
		$data['action'] = I('post.action','','trim,html_filter'); 
		if(!empty($data['action'])){
			$data['action'] = preg_replace('/\s+/i','',$data['action']);
			$data['action'] = str_replace('，',',',$data['action']);
		}
		
		$data['conditions'] = I('post.conditions','','trim'); 
		$data['get_params'] = I('post.get_params','','trim'); 
		$data['sort'] 		= I('post.sort',0,'int');
		$data['valid_status'] = I('post.valid_status',0,'int');
		
		$M = M('sys_navi');
		$result = $M->where("navi_id={$navi_id}")->save($data)===false ? false : true;	
		
		if($result){
			$this->response('success','操作成功');
		}else{
			$this->response('error','网络故障，操作失败');	
		}
		
	}
	
	public function navi_delete(){
		
		$navi_id = I('post.navi_id',0,'int');
		if(empty($navi_id)){
			$this->response('error','参数丢失');	
		}
		$Navi = D('Navi');
		$Navi->naviDelete($navi_id);
		$result = $Navi->where("navi_id={$navi_id}")->delete()===false ? false : true;
		
		if($result){
			$this->response('success','删除成功');
		}else{
			$this->response('error','网络故障，删除失败');	
		}

		
	}

}
