<?php
/**
 * SkinWidget Widget
 * @auther: kin
 * @date: 2016-08-17
 * @eg: W('Skin')
 */

class SkinWidget extends Widget{

	
	public function render($csses){
		
		$staff = D('Staff')->getLogin();
	
		return $this->renderFile('widget',array( 'csses'=>is_array($csses)?$csses:explode(',',$csses),'skin'=>$staff['skin'] ));
		
	}
		
}