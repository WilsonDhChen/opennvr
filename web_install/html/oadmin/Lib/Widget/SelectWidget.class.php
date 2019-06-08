<?php
/**
 * Select Widget
 * 多级select 联动
 * @date 2016-10-25
 * @eg:
 * W('Select',
 *	 array(
 *		'select_name'	=>'widget_select', 	//select 字段name名称
 *		'table'			=>'sys_config', 	//数据表(不含表前缀)
 *		'node_id'		=>'config_id', 		//节点id字段名
 *		'node_name'		=>'name',			//节点名称字段名
 *		'parent_id'		=>'parent_id',		//父级id字段名
 *		'max_level'		=>0,				//最大调用层级  0为无限制
 *		'top_id'		=>0,				//top_id 数据顶级parent_id
 *		'top_name'		=>'顶级节点名称',		//如果需求 需要选择到top_id 这一层及 请设置此项，一般此项名称为：顶级节点、一级节点
 *		'placeholder'	=>'请选择',			//占位符, 如果设置为false 则无占位符, 默认为: 请选择
 *		'where'			=>'',				//数据筛选where条件
 *		'order'			=>'sort desc',		//数据排序 请勿加 order by ,直接
 *		'selected'		=>'2,8,10,15'		//设定层级选定的option项值,多个以逗号分隔
 *		'disabled'		=>false				//slelct 是否 禁用 disabled
 *		'api'			=> '/xxx/api';		//ajax数据api URL
 *		)
 *	)
 * 
 */

class SelectWidget extends Widget{
	
	public function render($options){
		
		if(empty($options['table'])){
			return $this->trace_error('Not Set Data Table Name');	
		}
		
		$default = array('node_id'=>'node_id','node_name'=>'node_name','parent_id'=>'parent_id','top_id'=>0,'placeholder'=>'请选择','max_level'=>0,'where'=>'','order'=>'','api'=>U('api/select'));
		
		$config = array_merge($default,$options);
		unset($options,$default);
		
		//实例化标识计数
		static $index = 0;		
		$index++;
		//
		$vars = array();
		$vars['select_name'] = isset($config['select_name']) && !empty($config['select_name']) ? "{$config['select_name']}[]" : "widget_select_{$index}[]";
		$vars['table'] = $config['table'];
		$vars['top_id'] = $config['top_id'];
		$vars['top_name'] = $config['top_name'];
		$vars['parent_id'] = $config['parent_id'];
		$vars['node_id'] = $config['node_id'];
		$vars['node_name'] = $config['node_name'];
		$vars['placeholder'] = ($config['placeholder']==='' or $config['placeholder']===false or is_null($config['placeholder']))?'':'<option value="">'.$config['placeholder'].'</option>';
		$vars['max_level'] = $config['max_level'];
		$vars['where'] = $config['where'];
		$vars['order'] = $config['order'];
		$vars['selected'] = is_array($config['selected']) ? $config['selected'] : explode(',',$config['selected']);
		$vars['api'] = $config['api'];
		$vars['disabled'] = $config['disabled'];
		$vars['index'] = $index;
		
		$where = '';
		$where.= $config['parent_id'].'='.$config['top_id'];
		
		if(!empty($config['where'])){
			$where.=" AND ({$config['where']})";
		}
		if(empty($config['order'])){
			$order = "{$config['node_id']} asc";
		}else{
			$order = $config['order'];
		}
		$vars['data'] = M($config['table'])->field("{$config['node_id']},{$config['node_name']},{$config['parent_id']}")->where($where)->order($order)->select();
		$template = $this->renderFile('widget',$vars);
		return $template;		
	
	}
	
	
	private function trace_error($msg){
		return "<select><option>{$msg}</option></select>";
	}
	

		
}