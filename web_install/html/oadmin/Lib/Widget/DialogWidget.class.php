<?php
/**
 * Dialog Widget
 * @auther: kin
 * @date: 2016-08-18
 * @eg: W('Dialog')
 */

class DialogWidget extends Widget{

	
	public function render($data){
		
		return '<link rel="stylesheet" href="__STATIC__/tool/dialog/dialog.css" type="text/css"><script src="__STATIC__/tool/dialog/dialog.js"></script>';
	}
		
}