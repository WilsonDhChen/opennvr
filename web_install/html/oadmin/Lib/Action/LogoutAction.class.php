<?php
/**
 * OAdmin 退出登录
 */
 
class LogoutAction extends Action {
	
	//
	public function index(){
	
		D('Staff')->desLogin();
		redirect(__APP__.'/Login');
    }
	

	
	

}