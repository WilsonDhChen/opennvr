<?php
/**
 * css Widget
 * @date 2014-07-21
 * eg {:W('Css','global_index')}
 */

class CssWidget extends Widget{

	
	public function render($css_expr){
		
		$files = explode('_',$css_expr);

		$html = '';
		
		foreach($files as $file){
			$html.='<link rel="stylesheet" href="__STATIC__/css/'.$file.'.css" type="text/css" />';
		}
		return $html;			


					
	}
	
	

		
}