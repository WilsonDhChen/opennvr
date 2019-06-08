<?php
/**
 * Action基类
 * @auther : Kin
 * @data : 2014-08-25 11:04
 */
class BaseAction extends Action {
	
	//当前登录员工
	protected $staff;
	
	//当前权限
	protected $permit;
	
	/**
	 * 所有继承初始化操作 此方法由ThinkPHP提供，所有继承BaseAction的Action 不能再使用此方法。
	 * 
	 */
	final public function _initialize(){



		
		//登录检测
		$Staff = D('Staff');
		$this->staff = $Staff->getLogin();
		
		if( empty($this->staff) ){
            redirect(__APP__.'/Login');
		}
		//权限
		$Navi = D('Navi');
		$permit = $Navi->getPermit($this->staff['roles'],$this->staff);
		
		if( $permit === false ){
			$this->response('error','没有权限访问!');
		}
		$GLOBALS['page_power_data'] = array('permit'=>$permit,'staff'=>$this->staff);
		//$global_keyvalue = M()->table('config_global_keyvalue')->where("name = 'decive_name'")->find();
		//登录数据 赋值模版
		$this->assign('staff',$this->staff);
		//$this->assign('device_name_system',$global_keyvalue['value']);
        //所有继承BaseAction控制器初始化方法，防止覆盖BaseAction中的_initialize
		//也解决了Action中需要初始化时 需要调用 parent::_initialize();
        if(method_exists($this,'_init')){
			$this->_init();
		}
        
	}

}