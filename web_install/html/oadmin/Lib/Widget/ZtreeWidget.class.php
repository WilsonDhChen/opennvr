<?php
/**
 * Ztree Widget
 * @date 2014-07-21
 */

class ZtreeWidget extends Widget{
	
	public function render($config){
	
		$template = $this->renderFile('widget');

		return $template;		
	
	}
	

		
}