<?php
/**
 * OAdmin 主界面
 * @auther : Kin
 * @data : 2016-08-12 15:07
 */
 
class DesktopAction extends BaseAction {
	
	
	//主界面
	public function index(){
		
		$this->assign('sys_cnname',config('sys_cnname')->value());

		//获取当前登录员工角色名称
		$this->staff_roles = M('sys_role')->field('role_name')->where("role_id in (".(implode(',',$this->staff['roles'])).")")->select();

		$this->display();
    }
	
	
	//加载桌面应用
	public function deskapp(){
        $icons = include(CONF_PATH.'icon.php');
        $this->assign('icons', $icons);
		$this->assign('apps',D('Navi')->getDesktopApp($this->staff));
		
		$this->display();
	}	
	
	
	
	//加载应用页面
	public function pagetab(){
        
        $icons = include(CONF_PATH.'icon.php');
        $this->assign('icons', $icons);
		
		$appid 	 = I('get.appid',0,'int');
		$this->assign('appid',$appid);
		
		$Navi = D('Navi');
		$naviB = $Navi->naviUnique($Navi->getNaviB($appid,$this->staff['roles']));
		
		//无子栏目 暂时没有任何功能
		if(empty($naviB)){
			
	  		$this->assign('naviB',false);
	  		$this->assign('pagetab_src',false);
	  		$this->display();
			exit;		
			
		}		
						
		//权限 conditions字段判断、三级栏目检测
		foreach($naviB as &$tab){
			//conditions字段 判断
			if(!empty($tab['conditions']) && !eval('return '.$tab['conditions'].';')){
				unset($tab);
				continue;
			}
			unset($tab['conditions']);			
			//三级栏目naviC
			if(empty($tab['module'])){
				$naviC = $Navi->naviUnique($Navi->getNaviC($tab['navi_id'],$this->staff['roles']));
				//conditions字段 判断
				foreach($naviC as $key => $val){
					//conditions字段 判断
					if(!empty($val['conditions']) && !eval('return '.$val['conditions'].';')){
						unset($naviC[$key]);
					}
					//conditions字段判断后销毁
					unset($naviC[$key]['conditions']);
					//三级栏目 iframe src
					if(empty($val['module'])){
						$naviC[$key]['iframe'] = U('/Index/nothing');	
					}else{
						$naviC[$key]['iframe'] = U("/{$val['module']}/".$Navi->getNaviAction($val['action'])).$val['get_params'];
					}
				}			
				$tab['naviC'] = $naviC;
			}else{
				//二级栏目 iframe src
				$tab['iframe'] = U("/{$tab['module']}/".$Navi->getNaviAction($tab['action'])).$tab['get_params'];
			}
		}
		unset($tab);
		
		$this->assign('naviB',$naviB);
		$this->assign('pagetab_src',(empty($naviB[0]['iframe']) ? $naviB[0]['naviC'][0]['iframe'] : $naviB[0]['iframe']));
		$this->display();
			
	}
	
	
	public function nothing(){
		$appid 	 = I('get.appid',0,'int');
		$this->assign('appid',$appid);
		$this->display();	
	}
	
		
	

}
