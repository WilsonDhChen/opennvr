<?php

class NaviModel extends Model{

	protected $tableName = 'sys_navi';
	
	protected $config = array(
							//不需要验证的navi 格式， Test,Abc.aaa
							'rule_out' =>array('Navi','Desktop','Api','Ueditor'),
	);
	
	//获取当前登录角色桌面应用
	public function getDesktopApp($staff){
		
		//获取当前登录角色一级栏目(桌面APP)
		$naviA = $this->getNaviA($staff['roles']);
		
		if(empty($naviA)){
			return array();
		}

		//员工登录信息赋值 用于naiv conditions中特殊权限验证使用
		$this->staff = $staff;

		foreach($naviA as $key => &$val){
			//销毁不需要的字段
			unset($val['parent_id'],$val['module'],$val['action'],$val['insert_time']);
			//conditions字段 判断
			if(!empty($val['conditions']) && !eval('return '.$val['conditions'].';')){
				unset($naviA[$key]);
			}
			//conditions字段判断后销毁
			unset($val['conditions']);
		}
		
		return $naviA;
		
	}
	
	//获取当前登录角色一级栏目(桌面APP)
	public function getNaviA($roles){
		
		//如果是超级管理员角色 读取所有栏目
		if( $this->superAdminRole($roles) ){
			return $this->where("parent_id=0 and valid_status=1")->order('sort desc,navi_id asc')->select();
		}else{
			$role = " and navi_id in ".$this->field("navi_id")->table('__SYS_ROLE_ACCESS__')->where("role_id in (".implode(',',$roles).")")->buildSql();
			return $this->where("parent_id=0 and valid_status=1 {$role}")->order('sort desc,navi_id asc')->select();	
		}
		
	}
	
	//获取当前登录角色二级栏目(桌面APP二级栏目)
	public function getNaviB($parent_id,$roles){

		//如果是超级管理员角色
		if( $this->superAdminRole($roles) ){
			$naviB = $this->field('navi_id,parent_id,navi_name,module,action,conditions,get_params,insert_time')->where("valid_status=1 and parent_id={$parent_id}")->order('sort desc,navi_id asc')->select();
		}else{
			$naviB = $this->alias('a')->field('a.navi_id,a.parent_id,a.navi_name,a.module,a.action,a.conditions,a.get_params,a.insert_time')->join('__SYS_ROLE_ACCESS__ b on b.navi_id=a.navi_id')->where("a.valid_status=1 and a.parent_id={$parent_id} and b.role_id in(".implode(',',$roles).")")->order('a.sort desc,a.navi_id asc')->select();
		}		
		return $naviB;
		
	}
	
	//获取当前登录角色三级栏目
	public function getNaviC($parent_id,$roles){

		//如果是超级管理员角色
		if( $this->superAdminRole($roles) ){
			$naviC = $this->field('navi_id,parent_id,navi_name,module,action,conditions,get_params,insert_time')->where("valid_status=1 and parent_id={$parent_id}")->order('sort desc,navi_id asc')->select();
		}else{
			$roles_in =  " and b.role_id in(".implode(',',$roles).")";	
  			$naviC = $this->alias('a')->field('a.navi_id,a.parent_id,a.navi_name,a.module,a.action,a.conditions,a.get_params,a.insert_time')->join('__SYS_ROLE_ACCESS__ b on b.navi_id=a.navi_id')->where("a.valid_status=1 and a.parent_id={$parent_id} {$roles_in}")->order('a.sort desc,a.navi_id asc')->select();			
		}		
		
		return $naviC;
		
	}	
	
	//获取当前权限
	public function getPermit($roles,$staff){
		
		//当前访问module和action
		$module 	= strtolower(MODULE_NAME);
		$action 	= strtolower(ACTION_NAME);
		//不需要验证的module和action  小写容错处理
		$rule_out 	= array_map('strtolower',$this->config['rule_out']);		
		
		if(in_array($module,$rule_out) || in_array("{$module}.{$action}",$rule_out)){
			return true;		
		}
		
		$superadmin = $this->superAdminRole($roles);
		
		//如果是超级管理员角色
		if( $superadmin ){
			$roles_in = '';
		}else{
			$roles_in =  " and b.role_id in(".implode(',',$roles).")";	
		}		
		
		//获取当前角色是否具有当前(页面)权限
		$navi = $this->alias('a')->field('a.navi_id,a.conditions')->join('__SYS_ROLE_ACCESS__ b on b.navi_id=a.navi_id')->where("a.valid_status=1 and a.module='{$module}' and find_in_set('{$action}',lower(a.action)) {$roles_in}")->find();
		
		//员工登录信息赋值 用于naiv conditions中特殊权限验证使用
		$this->staff = $staff;
		//没有权限
		if(empty($navi)){
			 return false;	
		}else if(!empty($navi['conditions']) && !eval('return '.$navi['conditions'].';')){
		//特殊权限限制
			return false;
		}
		
		//获取当前页面所有子权限
		if( $superadmin ){
			$permit = $this->query("select navi_id,module,action,conditions from __PREFIX__sys_navi where parent_id={$navi['navi_id']} and valid_status=1");
		}else{
			$permit = $this->query("select a.navi_id,a.module,a.action,a.conditions from __PREFIX__sys_navi as a,__PREFIX__sys_role_access as b,__PREFIX__sys_role as c where a.navi_id=b.navi_id  and b.role_id=c.role_id  and a.parent_id={$navi['navi_id']} and c.role_id in(".implode(',',$roles).") and a.valid_status=1");
		}
			
		return (array) $permit;
		
	}
	
	
	//获取共享权限
	public function getSPermit($roles,$staff,$module,$action){

		//不需要验证的module和action  小写容错处理
		$rule_out 	= array_map('strtolower',$this->config['rule_out']);		
		
		if(in_array($module,$rule_out) || in_array("{$module}.{$action}",$rule_out)){
			return true;		
		}
		
		$superadmin = $this->superAdminRole($roles);
		
		//如果是超级管理员角色
		if( $superadmin ){
			$roles_in = '';
		}else{
			$roles_in =  " and b.role_id in(".implode(',',$roles).")";	
		}		
		
		//获取当前角色是否具有当前(页面)权限
		$navi = $this->alias('a')->field('a.navi_id,a.conditions')->join('__SYS_ROLE_ACCESS__ b on b.navi_id=a.navi_id')->where("a.valid_status=1 and a.module='{$module}' and find_in_set('{$action}',lower(a.action)) {$roles_in}")->order('a.navi_id asc')->find();
		//员工登录信息赋值 用于naiv conditions中特殊权限验证使用
		$this->staff = $staff;
		//没有权限
		if(empty($navi)){
			 return false;	
		}else if(!empty($navi['conditions']) && !eval('return '.$navi['conditions'].';')){
		//特殊权限限制
			return false;
		}
		
		return true;
		
	}	
	
	
	
	
	//获取指定用户组是否是超级管理员
	public function superAdminRole($roles){
		
		return in_array(C('SUPER_ADMIN_ROLE'),$roles);
		
	}
	
	
	//递归删除一个栏目
	public function naviDelete($navi_id){
		
		$children = $this->where("parent_id in({$navi_id})")->getField('GROUP_CONCAT(navi_id) as children');
		if(empty($children)){
			return true;	
		}
		$this->where("navi_id in({$children})")->delete();
		$this->naviDelete($children);
				
	}
	
	//去除重复的权限栏目
	public function naviUnique($navis){
		
		$navi_ids = array();
		foreach($navis as $key=>$val){
			if(in_array($val['navi_id'],$navi_ids)){
				unset($navis[$key]);
			}else{
				$navi_ids[] = $val['navi_id'];	
			}
		}
		
		return $navis;
		
	}
	
	//栏目中获取默认页面action
	public function getNaviAction($action){
		return trim(current(explode(',',$action)));
			
	}	

  	
}