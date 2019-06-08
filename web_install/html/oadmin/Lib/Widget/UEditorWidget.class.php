<?php
/**
 * UEditor Widget
 * @date 2014-07-21
 */

class UEditorWidget extends Widget{
	
	public function render($config){
		
		static $count = 1;
		//模版数据和配置数据分离
		$data = array();
		
		//模版数据
		$data['content'] 	= $config['content'];
		$data['id'] 		= empty($config['id']) 	   ? 'UEditor_instance' : $config['id'];
		$data['var'] 		= empty($config['var'])    ? 'editor' : $config['var'];
		$data['name'] 		= empty($config['name'])   ? 'content' : $config['name'];
		$data['width'] 		= empty($config['width'])  ? '100%' : $config['width'];
		$data['height']   	= empty($config['height']) ? '300px' : $config['height'];
		//文件上传接口
		$config['serverUrl']= empty($config['serverUrl']) ? U('/Ueditor') : $config['serverUrl'];
		
		//销毁模版变量数据
		unset($config['content'],$config['id'],$config['var'],$config['name'],$config['width'],$config['height']);
		
		//编辑器配置数据
		$data['config'] = empty($config)?'':','.json_encode($config);
		$data['count'] = $count;
		$template = $this->renderFile('widget',$data);
		$count++;
		return $template;		
	
	}
	

		
}