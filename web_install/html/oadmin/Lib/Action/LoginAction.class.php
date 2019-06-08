<?php
/**
 * OAdmin 管理登录
 */
class LoginAction extends Action {
	
    
	public function index(){

		$this->assign('sys_cnname',config('sys_cnname')->value());
		
		$this->display();	
    }
	
	
	public function post(){
		
		$this->response( D('Staff')->verLogin() );
	}
	

}