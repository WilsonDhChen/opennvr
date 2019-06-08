<?php
/**
 * Config Widget
 * 依赖 Select Widget
 * @CreatedAt 2016-10-25
 * @UpdatedAt 2017-05-03
 * @eg
 * W('Config','identifier_name')   //使用配置标识identifier 调用,
 * W('Config',8) 				   //使用配置config_id 调用
 * W('Config',array('identifier'=>'identifier_name','select_name'=>'myselect_name'))   //设置select name
 * W('Config',array('identifier'=>'identifier_name','selected'=>'2,8,10,15'))   //设置默认选择option
 *
 */

class ConfigWidget extends Widget{

	const TABLE = 'sys_config';

	public function render($options){

		if(is_array($options)){

			if(empty($options['identifier'])){
				$options['top_id'] = empty($options['top_id']) ? 0 : $options['top_id'];
			}else{
				$options['top_id'] = M(self::TABLE)->where("identifier='{$options['identifier']}'")->getField('config_id');
			}

		}else{

			$param = $options;

			$options = array();

			if(is_numeric($param)){
				$options['top_id'] = $param;
			}else if(is_string($param)){
				$options['top_id'] = M(self::TABLE)->where("identifier='{$param}'")->getField('config_id');
			}else{
				$options['top_id'] = 0;
			}

		}

		$options['table'] 		= self::TABLE;
		$options['node_id'] 	= 'config_id';
		$options['node_name'] 	= 'name';
		$options['parent_id']	= 'parent_id';
		$options['order'] 		= empty($options['order'])? 'sort desc,config_id asc' : $options['order'];

		return W('Select',$options,true);

	}




}