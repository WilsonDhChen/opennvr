<?php
/**
 * jQuery Widget
 * @date 2014-07-21
 * @eg W('jQuery')  W('jQuery','1.10.2'); 
 */

class jQueryWidget extends Widget{
	//配置
	protected $config = array(
							//全局默认jQuery 版本 W('jQuery') 
	  						'default_version' =>'1.11.1',
	);
	
	public function render($use_version){
		//调用指定版本  W('jQuery','1.10.2');
		if( is_string($use_version) && strlen($use_version)>0 ){
			$jQuery = '__STATIC__/js/jquery/jquery-'.trim($use_version).'.min.js';
		}else{
		//默认没有指定版本 W('jQuery');
			$jQuery = '__STATIC__/js/jquery/jquery-'.$this->config['default_version'].'.min.js';	
		}
		
		return '<script src="'.$jQuery.'" type="text/javascript"></script>';
	}

		
}