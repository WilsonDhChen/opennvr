<?php

class MyAction extends BaseAction{



	public function info(){
	
		$staff_id = $this->staff['staff_id'];
		$this->assign('staff_id',$staff_id);
		
		$this->rs = M('sys_staff')->where("staff_id={$staff_id} and delete_status=1")->find();
		if(empty($this->rs)){
			$this->response('error','不存在此员工信息');		
		}
				
		$this->display();	
		
	}
	
	public function edit_post(){
		
		$staff_id = $this->staff['staff_id'];
		$data = array();
		$data['realname'] = I('post.realname','','trim,html_escape');
		$data['gender'] = I('post.gender','','trim,html_escape');		
		$data['qq'] = I('post.qq','','trim,html_escape');		
		$data['email'] = I('post.email','','trim,html_escape');
		$data['cellphone'] = I('post.cellphone','','trim,html_escape');
		$sys_staff = M('sys_staff');
		$result = $sys_staff->where("staff_id={$staff_id}")->save($data) ===false ? false : true;
		if(empty($result)){
			$this->response('error','网络故障，修改失败 请重试');
		}
		$this->response('success','修改资料成功');
		
	}
	
	public function wallpaper(){
		
		$wallpaper = D('staff')->where("staff_id={$this->staff['staff_id']}")->getField('wallpaper');
		if(empty($wallpaper) || json_decode($wallpaper,true)==NULL){
			$wallpaper = C('WALLPAPER');	
		}else{
			$wallpaper = json_decode($wallpaper,true);
		}
		
		$this->assign('wallpaper',$wallpaper);
		$this->display();
	}
	
	public function wallpaper_post(){
		
		//是否修改壁纸图片
		if($_FILES['image']['error']==UPLOAD_ERR_NO_FILE){
			$modify_image = false;
		
		}else{
			$modify_image = true;
			
			$upload_config = array('max_size' => '5MB',	'save_path'	=>APP_UFS.'/image/'.date('Y/m') , 'ext_type'=> array('jpg','jpeg','png'));
			$WebUploader = new WebUploader($upload_config);
			$result =  $WebUploader->upload('image') ;
			if(!$result){
				$this->response('error',$WebUploader->getError());	
			}
				
		}
		
		//默认壁纸
		$wallpaper = C('WALLPAPER');
		$default = $wallpaper['image'];
		$Staff = D('Staff');
		//获取上次壁纸
		$last = json_decode($Staff->where("staff_id={$this->staff['staff_id']}")->getField('wallpaper'),true);
		
		if(empty($last)){
			if($modify_image){
				$wallpaper['image'] = ltrim($result['save_path'].'/'.$result['new_name'],'.');
			}
		}else{
			if($modify_image){
				$wallpaper['image'] = ltrim($result['save_path'].'/'.$result['new_name'],'.');
			}else{
				$wallpaper['image'] = $last['image'];	
			}				
		}
		
		$wallpaper['color'] = I('post.color','','input_filter') ? I('post.color','','input_filter') : $wallpaper['color'];
		$wallpaper['position'] = I('post.position','','input_filter') ? I('post.position','','input_filter') : $wallpaper['position'];
		

		$result = $Staff->where("staff_id={$this->staff['staff_id']}")->setField('wallpaper',json_encode($wallpaper)) === false ? false : true;
		if($result){
			//如果上次使用的不是默认壁纸 则删除上次使用的壁纸
			if(!empty($last) && is_array($last) && $modify_image && $last['image']!=$default){
					@unlink('.'.$last['image']);
			}
			$this->response('success','设置壁纸成功,即将刷新页面显示效果。<script type="text/javascript">setTimeout(function(){ window.top.location.reload(); },1000)</script>');
		}else{
			$this->response('error','网络故障，设置壁纸失败');
		}
	}	
	
	public function password(){
		
		$this->staff_id = $this->staff['staff_id'];
	  	$this->display();	
	}
	
	public function password_post(){
		
		$staff_id = $this->staff['staff_id'];
		$oldpassword = I('post.oldpassword','');
		$password = I('post.password','');
		
		if(empty($oldpassword)){
			$this->response('error','请输入原密码');
		}
		
		if(empty($password)){
			$this->response('error','新密码不能为空');
		}
		
		$Staff = D('Staff');
		
		if($Staff->verifyPassword($oldpassword,$staff_id) !== true){
			$this->response('error','原密码不正确');
		}
		if(strlen($password)>32 || strlen($password)<6){
			$this->response('error','新密码长度为 6-32 位');
		}
		
		$result = $Staff->resetPassword($password,$staff_id);
		if($result['status']=='success'){
			$Staff->desLogin();
			$this->response('success','修改密码成功');
		}else{
			$this->response('error','网络故障 修改密码失败');	
		}
	}
	
}