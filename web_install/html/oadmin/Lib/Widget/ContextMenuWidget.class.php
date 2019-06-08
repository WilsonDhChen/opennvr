<?php
/**
 * ContextMenu Widget
 * @auther: kin
 * @date: 2016-08-21
 * @eg: W('ContextMenu')
 */

class ContextMenuWidget extends Widget{

	
	public function render($data){
		
		return '<link rel="stylesheet" href="__STATIC__/tool/contextMenu/contextMenu.css" type="text/css"><script src="__STATIC__/tool/contextMenu/contextMenu.js"></script>';
	}
		
}