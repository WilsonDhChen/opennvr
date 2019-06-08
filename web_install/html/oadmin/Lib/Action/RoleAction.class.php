<?php

class RoleAction extends BaseAction{
	
	public function index(){
		
		$m_role = M('sys_role');
		
		$count = $m_role->count();
		$Page = D('Page');
		$this->page_html = $Page->create($count,'sys_grid',20);			
		
		$this->grid_data = $m_role->alias('a')
								  ->field("a.*,(select count(1) from ".__PREFIX__."sys_role_staff where role_id=a.role_id) as role_count")
								  ->order("a.insert_time asc")
								  ->limit($Page->limit[0].','.$Page->limit[1])
								  ->select();
	
		$this->display();
		
	}
	
	
	public function add(){
		
	
		$this->display();	
	}
	
	public function add_post(){
	
		$role_name = I('post.role_name','','html_escape');
		if(empty($role_name)){
		  $this->response('error','角色名称不能为空');	
		}
		
		$sys_role = M('sys_role');
		if( $sys_role->where("role_name='{$role_name}'")->count()){
			$this->response('error','角色名称已经存在');	
		}
		
		$role_id = $sys_role->add(array('role_name'=>$role_name,'insert_time'=>$_SERVER['REQUEST_TIME']));
		if(empty($role_id)){
			$this->response('error','网络故障，创建角色失败');	
		}
		$sys_role_access = M('sys_role_access');
		$navis = array_unique($_POST['navis']);
		
		//超级管理员
		if(in_array(C('SUPER_ADMIN_ROLE'),$this->staff['roles'])){
			foreach($navis as $navi_id){
				$sys_role_access->add(array('role_id'=>$role_id,'navi_id'=>$navi_id));	
			}				
		}else{
		//其他角色
			//获取其自身角色所有权限navi_id
			$navi_valid = array_unique(explode(',',$sys_role_access->where("role_id in(".implode(',',$this->staff['roles']).")")->getField('group_concat(navi_id)') ));
			foreach($navis as $navi_id){
				if(in_array($navi_id,$navi_valid)){
					$sys_role_access->add(array('role_id'=>$role_id,'navi_id'=>$navi_id));	
				}
			}			
		}

		$this->response('success','角色创建成功');	
		
	}
	
	public function edit(){
		
		$role_id = I('get.role_id',0,'intval');
		if(empty($role_id)){
			$this->response('error','参数不正确或是丢失');	
		}
		
		if($role_id==C('SUPER_ADMIN_ROLE')){
			$this->response('error','此角色权限无法修改');	
		}
		
		$sys_role = M('sys_role');
		$sys_role_access = M('sys_role_access');
		
		$role = $sys_role->where("role_id={$role_id}")->find();
		if(empty($role)){
			$this->response('error','不存在角色');
		}
		
		$navis = $sys_role_access->where("role_id={$role_id}")->getField("group_concat(navi_id)");
		
		if(empty($navis)){
			$role['navis'] = array();
		}else{
			$role['navis'] = explode(',',$navis);
		}
		
		$this->assign('role',$role);
		$this->assign('role_id',$role_id);
		$this->display();	
	}	
	
	public function edit_post(){

		$role_id = I('post.role_id',0,'intval');
		
		if(empty($role_id)){
		  $this->response('error','参数丢失');	
		}
		
		if($role_id==C('SUPER_ADMIN_ROLE')){
			$this->response('error','此角色权限无法修改');	
		}		
		
		$role_name = I('post.role_name','','html_escape');
		if(empty($role_name)){
		  $this->response('error','角色名称不能为空');	
		}
		
		$sys_role = M('sys_role');
		if( $sys_role->where("role_name='{$role_name}' and role_id!={$role_id}")->count()){
			$this->response('error','角色名称已经存在');	
		}
		
		$result = $sys_role->where("role_id={$role_id}")->setField('role_name',$role_name)===false ? false : true;
		if(!$result){
			$this->response('error','网络故障，编辑角色失败');
		}
		
		$navis = array();
		$navis['add'] = $_POST['add'];
		$navis['del'] = $_POST['del'];
		
		$sys_role_access = M('sys_role_access');
		//新增的权限
		if($navis['add']){
			//超级管理员
			if(in_array(C('SUPER_ADMIN_ROLE'),$this->staff['roles'])){			
				foreach($navis['add'] as $navi_id){
					$sys_role_access->add(array('role_id'=>$role_id,'navi_id'=>$navi_id));	
				}
			}else{
			//其他角色
				//获取其自身角色所有权限navi_id
				$navi_valid = array_unique(explode(',',$sys_role_access->where("role_id in(".implode(',',$this->staff['roles']).")")->getField('group_concat(navi_id)') ));			
				foreach($navis['add'] as $navi_id){
					if(in_array($navi_id,$navi_valid)){
						$sys_role_access->add(array('role_id'=>$role_id,'navi_id'=>$navi_id));	
					}
				}					
			}
		}
		
		//删除的权限
		if($navis['del']){
			$del_ids = implode(',',$navis['del']);
			$sys_role_access->where("role_id={$role_id} and navi_id in({$del_ids})")->delete();	
		}		
		
		$this->response('success','角色编辑成功');	
		
	}	
	
	public function delete_post(){
		
		$role_id = I('post.role_id',0,'intval');
		
		if(empty($role_id)){
			$this->response('error','参数丢失');
		}
		
		if($role_id==C('SUPER_ADMIN_ROLE')){
			$this->response('error','此角色权限无法删除');	
		}		
		
		$sys_role = M('sys_role');
		$sys_role_access = M('sys_role_access');
		
		$result = $sys_role->where("role_id={$role_id}")->delete()===false ? false :true;
		if(!$result){
			$this->response('error','网络故障，删除角色失败');
		}
		$sys_role_access->where("role_id={$role_id}")->delete();
		//
		$this->response('success','删除角色成功');	
		
	}
	
	public function load(){
		
		$id = I('post.id',0,'intval');
		$M = M('sys_navi');
		//超级管理员获取所有栏目
		if(in_array(C('SUPER_ADMIN_ROLE'),$this->staff['roles'])){
			$list = $M->query("select navi_id as id,navi_name as name,if((select count(1) from __PREFIX__sys_navi where parent_id=a.navi_id )>0,'true','false') as isParent from __PREFIX__sys_navi as a where parent_id={$id}");
		}else{
		//非超级管理员角色只能操作其自己角色所拥有的权限
			$list = $M->query("select navi_id as id,navi_name as name,if((select count(1) from __PREFIX__sys_navi where parent_id=a.navi_id )>0,'true','false') as isParent from __PREFIX__sys_navi as a where a.parent_id={$id} and a.navi_id in (select distinct navi_id from __PREFIX__sys_role_access where role_id in(".implode(',',$this->staff['roles']).")) and valid_status=1");
		}
		exit(json_encode($list));
	}	
	
}