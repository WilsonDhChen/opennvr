<?php


class BootstrapWidget extends Widget{

	
	public function render($data){
		
		return '<link rel="stylesheet" href="__STATIC__/tool/bootstrap/css/bootstrap.min.css" type="text/css"><script src="__STATIC__/tool/bootstrap/js/bootstrap.min.js"></script>';
	}
		
}