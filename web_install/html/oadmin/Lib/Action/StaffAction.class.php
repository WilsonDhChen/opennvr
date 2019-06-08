<?php

class StaffAction extends BaseAction{
	

	public function index(){
		
		
		$sys_staff = M('sys_staff');
		
		$cellphone = I('get.cellphone','','html_escape');
		$qq = I('get.qq','','html_escape');
		$username = I('get.username','','html_escape');
		$realname = I('get.realname','','html_escape');
		$gender = I('get.gender','','html_escape');
		$job_status = I('get.job_status','','html_escape');
		$role = I('get.role',0,'intval');
	
		
		//是否超级管理员身份
		$this->super_admin = D('Navi')->superAdminRole($this->staff['roles']);		
		
		$where = 'a.staff_id=b.staff_id and a.delete_status=1 ';
		
		if($cellphone) $where .= " and a.cellphone like '%{$cellphone}%' ";
		if($qq) $where .= " and a.qq like '%{$qq}%' ";
		if($username) $where .= " and a.username like '%{$username}%' ";
		if($realname) $where .= " and a.realname like '%{$realname}%' ";
		if($job_status!='') $where .= " and a.job_status = '{$job_status}' ";
		if($gender) $where .= " and a.gender = '{$gender}' ";
		if($role) $where .=" and b.role_id={$role}";
		if(!$this->super_admin)  $where .=" and b.role_id<> ".C('SUPER_ADMIN_ROLE');
		
		$sort = I('get.sort','','strip_tags,html_escape');
		if($sort){
			$order = 'a.'.str_replace('.',' ',$sort);	
		}else{
			$order = "a.job_status desc,a.staff_id desc";		
		}


		
		$count = current($sys_staff->query("select count(*) as total from __PREFIX__sys_staff as a,__PREFIX__sys_role_staff as b where {$where} group by a.staff_id"));
		$Page = D('Page');
		$this->page_html = $Page->create($count['total'],'sys_grid',20);		

							
		$this->grid_data = $sys_staff->query("select a.*,(select group_concat(role_name) from __PREFIX__sys_role where role_id in(select role_id from __PREFIX__sys_role_staff where staff_id=a.staff_id)) as roles,if(( select count(1) from __PREFIX__sys_role_staff where staff_id=a.staff_id and role_id=".C('SUPER_ADMIN_ROLE').")>0,1,0) as isadmin from __PREFIX__sys_staff as a,__PREFIX__sys_role_staff as b where {$where} group by a.staff_id order by {$order} limit {$Page->limit[0]},{$Page->limit[1]}");
		
		if($this->super_admin){
			//获取所有角色
			$this->roles = M('sys_role')->select();
		}else{
			//获取除管理员以外的所有角色
			$this->roles = M('sys_role')->where("role_id<>".C('SUPER_ADMIN_ROLE'))->select();				
		}
		
							
		$this->assign('cellphone',$cellphone);
		$this->assign('qq',$qq);
		$this->assign('username',$username);
		$this->assign('realname',$realname);
		$this->assign('job_status',$job_status);
		$this->assign('gender',$gender);
		$this->assign('role',$role);
		$this->assign('search',I('get.search'));
		
		$this->display();
	}
	
	
	public function add(){
		

  		//是否超级管理员身份		
		$this->super_admin = D('Navi')->superAdminRole($this->staff['roles']);
		if($this->super_admin){
			$where = '1';		
		}else{
			$where = "role_id!=".C('SUPER_ADMIN_ROLE');	
		}
		//获取所有角色
		$this->roles = M('sys_role')->where($where)->select();		
		
		$this->display();	
	}
	
	
	public function add_post(){
		
		$data = array();
		$data['username'] = I('post.username','','trim,html_escape');
		if(empty($data['username'])){
			$this->response('error','员工帐号不能为空');
				
		}
		
		if(D('Staff')->verifyUsername($data['username'])){
			$this->response('error','员工帐号与现有在职员工重复');	
		}
		
		if(empty($_POST['password'])){
			$this->response('error','登录密码不能为空');
				
		}		
		$data['password'] = I('post.password','','pwd_md5');
		
		if(empty($_POST['roles'])){
			$this->response('error','请选择员工权限角色');	
		}
		if(in_array(C('SUPER_ADMIN_ROLE'),$_POST['roles']) && count($_POST['roles'])>1){
			$this->response('error','超级管理员角色不能和其他角色并列选择');		
		}
		
		//获取当前被编辑用户权限组
		$Navi = D('Navi');	
		//当前操作员工(登录员工)的是否是超级管理员
		$this->super_admin = $Navi->superAdminRole($this->staff['roles']);
		//当前被编辑的用户是否是超级管理员
		$add_is_has_admin =  $Navi->superAdminRole($_POST['roles']);
		
		//如果非超级管理员用户 修改超级管理员资料，提示不允许
		if($add_is_has_admin && !$this->super_admin){
			$this->response('error','你所在的角色组无权限添加此角色员工！');
		}			
		
		$data['realname'] = I('post.realname','','trim,html_escape');
		$data['gender'] = I('post.gender','','trim,html_escape');		
		$data['qq'] = I('post.qq','','trim,html_escape');		
		$data['email'] = I('post.email','','trim,html_escape');
		$data['cellphone'] = I('post.cellphone','','trim,html_escape');
		$data['job_status'] = I('post.job_status',0,'intval');
		$data['entry_date'] = I('post.entry_date','','trim,html_escape');
		$data['insert_time'] = $_SERVER['REQUEST_TIME'];
		$sys_staff = M('sys_staff');
		$staff_id = $sys_staff->add($data);
		
		if(empty($staff_id)){
			$this->response('error','网络故障，添加失败 请重试');
		}
		
		$sys_role_staff = M('sys_role_staff');
		foreach($_POST['roles'] as $role_id){
			$sys_role_staff->add(array('role_id'=>$role_id,'staff_id'=>$staff_id,'insert_time'=>$_SERVER['REQUEST_TIME']));	
		}
		
		$this->response('success','添加员工成功');
		
	}
	
	
	public function edit(){
		
		$staff_id = I('get.staff_id',0,'intval');
		if(empty($staff_id)){
			$this->response('error','参数丢失');	
		}
		$this->assign('staff_id',$staff_id);
		
		$this->rs = M('sys_staff')->where("staff_id={$staff_id} and delete_status=1")->find();
		if(empty($this->rs)){
			$this->response('error','不存在此员工信息');		
		}
		
		$this->role = explode(',',M('sys_role_staff')->where("staff_id={$staff_id}")->getField("group_concat(role_id)"));
		
  		//是否超级管理员身份	
		$Navi = D('Navi');	
		$this->super_admin = $Navi->superAdminRole($this->staff['roles']);
		
		$edit_is_admin =  $Navi->superAdminRole($this->role);
		
		//如果非超级管理员用户 修改超级管理员资料，提示不允许
		if($edit_is_admin && !$this->super_admin){
			$this->response('error','你所在的角色组无权限编辑此用户信息！');
		}
		
		if($this->super_admin){
			$where = '1';		
		}else{
			$where = "role_id!=".C('SUPER_ADMIN_ROLE');	
		}
		//获取所有角色
		$this->roles = M('sys_role')->where($where)->select();		
		
		$this->display();		
	}
	
	
	
	public function edit_post(){
		
		$staff_id = I('post.staff_id',0,'intval');
		if(empty($staff_id)){
			$this->response('error','参数丢失');	
		}
		
		if(!M('sys_staff')->where("staff_id={$staff_id} and delete_status=1")->count()){
			$this->response('error','不存在此员工信息');		
		}			
		
  		//是否超级管理员身份	
		//获取当前被编辑用户权限组
		$this->role = explode(',',M('sys_role_staff')->where("staff_id={$staff_id}")->getField("group_concat(role_id)"));
		$Navi = D('Navi');	
		//当前操作员工(登录员工)的是否是超级管理员
		$this->super_admin = $Navi->superAdminRole($this->staff['roles']);
		//当前被编辑的用户是否是超级管理员
		$edit_is_admin =  $Navi->superAdminRole($this->role);
		
		//如果非超级管理员用户 修改超级管理员资料，提示不允许
		if($edit_is_admin && !$this->super_admin){
			$this->response('error','你所在的角色组无权限编辑此员工信息！');
		}			
		
		$data = array();
		$data['username'] = I('post.username','','trim,html_escape');
		if(empty($data['username'])){
			$this->response('error','员工帐号不能为空');
				
		}
		
		if(D('Staff')->verifyUsername($data['username'],$staff_id)){
			$this->response('error','员工帐号与现有在职员工重复');	
		}		
		
		if(empty($_POST['roles'])){
			$this->response('error','请选择员工权限角色');	
		}
		if(in_array(C('SUPER_ADMIN_ROLE'),$_POST['roles']) && count($_POST['roles'])>1){
			$this->response('error','超级管理员角色不能和其他角色并列选择');		
		}
		$data['realname'] = I('post.realname','','trim,html_escape');
		$data['gender'] = I('post.gender','','trim,html_escape');		
		$data['qq'] = I('post.qq','','trim,html_escape');		
		$data['email'] = I('post.email','','trim,html_escape');
		$data['cellphone'] = I('post.cellphone','','trim,html_escape');
		$data['job_status'] = I('post.job_status',0,'intval');
		$data['entry_date'] = I('post.entry_date','','trim,html_escape');
		$sys_staff = M('sys_staff');
		$result = $sys_staff->where("staff_id={$staff_id}")->save($data) ===false ? false : true;
		if(empty($result)){
			$this->response('error','网络故障，修改失败 请重试');
		}
		
		$sys_role_staff = M('sys_role_staff');
		//获取现有权限组
		$a_roles = explode(',',$sys_role_staff->where("staff_id={$staff_id}")->getField("group_concat(role_id)"));
		//遍历本次 新的权限组
		foreach($_POST['roles'] as $role_id){
			//查找本次更新权限组 是否在 已有权限组 
			$key = array_search($role_id,$a_roles);
			//如果没有给员工增加权限组
			if($key===false){
				$sys_role_staff->add(array('role_id'=>$role_id,'staff_id'=>$staff_id,'insert_time'=>$_SERVER['REQUEST_TIME']));
			}else{
				//删除已经本次更新和 原来 都存在的权限组
				unset($a_roles[$key]);					
			}
		}
		//a_roles 剩下的 表示 取消授权的 权限组，需要删除
		$sys_role_staff->where("staff_id={$staff_id} and role_id in(".implode(',',$a_roles).")")->delete();
		
		$this->response('success','修改员工成功');
		
	}
	
	
	public function delete(){
		
		
		$staff_id = I('post.staff_id',0,'intval');
		if(empty($staff_id)){
			$this->response('error','参数丢失');
		}
		$sys_staff = M('sys_staff');
		if(!($sys_staff->where("staff_id={$staff_id} and delete_status=1")->count())){
			$this->response('error','不存在此员工信息');		
		}			
		
  		//是否超级管理员身份	
		//获取当前被编辑用户权限组
		$this->role = explode(',',M('sys_role_staff')->where("staff_id={$staff_id}")->getField("group_concat(role_id)"));
		$Navi = D('Navi');	
		//当前操作员工(登录员工)的是否是超级管理员
		$this->super_admin = $Navi->superAdminRole($this->staff['roles']);
		//当前被编辑的用户是否是超级管理员
		$delete_is_admin =  $Navi->superAdminRole($this->role);
		
		//如果非超级管理员用户 删除超级管理员，提示不允许
		if($delete_is_admin && !$this->super_admin){
			$this->response('error','你所在的角色组无权限删除此员工！');
		}
		
		$result = $sys_staff->where("staff_id={$staff_id}")->save(array('delete_status'=>'0'));
		if($result){
			//删除此员工所有的角色关联 关系
			M('sys_role_staff')->where("staff_id={$staff_id}")->delete();
			$this->response('success','删除员工成功');	
		}else{
			$this->response('error','网络故障，删除员工失败 请重试');
		}
			
		
	}	
	
	
	public function password(){
		
		$this->staff_id = I('get.staff_id',0,'intval');
		if(empty($this->staff_id)){
			$this->response('error','参数丢失');
		}		
		
	  $this->display();	
	}
	
	public function password_post(){
		
		$staff_id = I('post.staff_id',0,'intval');
		if(empty($staff_id)){
			$this->response('error','参数丢失');
		}
		
		$password = I('post.password','');
		if(empty($password)){
			$this->response('error','新密码不能为空');
		}
		
		$result = D('Staff')->resetPassword($password,$staff_id);
		if($result['status']=='success'){
			$this->response('success','修改密码成功');
		}else{
			$this->response('error','网络故障 修改密码失败');	
		}
		
	}


}
