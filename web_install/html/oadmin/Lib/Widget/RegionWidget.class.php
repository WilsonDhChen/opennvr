<?php
/**
 * Region Widget
 * @author Kin
 */

class RegionWidget extends Widget{
	
	public function render($var){
		//实例化计数
		static $count = 0;

		
		$data = array();
		$data['count'] = $count;
		//联动层级 1省 ，2省市，3省市区
		//如果没有指定level
		if(empty($var['level'])){
			//检测默认值是否指定，按默认值个数决定levle
			if($var['value']){
				$value_length	= substr_count($var['value'],',') + 1;
				//默认值长度 如果合法，在1~3范围内，则启用 默认值长度为 联动level
				if(in_array($value_length,array(1,2,3))){
					$data['level'] = $value_length;	
				}else{
				//否则使用默认值3 为默认level
					$data['level']  = 3;
				}
			}else{
			//如果没有指定默认值并且level没有传递 则使用默认level
				$data['level']  = 3;		
			}
		}else{
			//如果指定了level 并且在1~3范围内，则使用传递的level,否则使用默认level
			$data['level']  = in_array($var['level'],array(1,2,3)) ? $var['level'] : 3 ;
		}
		
		//数据
		$data['region_data'] = $this->get_region_data($var['value'],$var['name'],$data['level'],$count);
		//print_r($data['region_data'] );exit;
		//region名称显示，默认显示简称 short设置为false 即可显示全称
		$data['region_name_field'] = (isset($var['short']) && $var['short']==false) ? 'name':'short_name';
		//region 联动 ajax获取地址
		$data['region_ajax_api'] = empty($var['api']) ? U('api/region') : $var['api'];
		
		$count++;
		$template = $this->renderFile('widget',$data);
		return $template;		
	
	}
	
	//
	private function get_region_data($value,$name,$level,$count){
		
		$M = M('com_region');
		$data = array();
		//
		$selected_value	= array();
		$selected_count	= 0;
		if(!empty($value)){
			$selected_value = explode(',',$value);
			$selected_count	= count($selected_value);
		}		
		
		//select name
		//如果多次调用 自动加上后缀
		$suffix = $count==0 ? '' : $count;		
		$select_name = empty($name) ? array() : explode(',',$name);
		
		//省份
		$province = array();
		$province['level'] 			= 1;
		$province['area_name'] 		= '省份';
		$province['select_name'] 	= empty($select_name[0]) ? "region{$suffix}[province]" : $select_name[0];
		$province['options']  		= $M->field('id,parent_id,name,short_name,level_type')->where("level_type=1")->order("id asc")->select();
		//设置选择默认值
		if(!empty($selected_value[0])){
			$province['selected'] 	= $selected_value[0];	
		}
		
		//城市
		if($level>1){
			$city = array();
			$city['level'] 			= 2;
			$city['area_name'] 		= '城市';
			$city['select_name'] 	= empty($select_name[1]) ? "region{$suffix}[city]" : $select_name[1];
			if(empty($selected_value[1])){
				if(!empty($province['selected'])){
					$city['options']  	= $M->field('id,parent_id,name,short_name,level_type')->where("parent_id={$province['selected']}")->order("id asc")->select();			
				}
			}else{
				$city['selected'] 	= $selected_value[1];
				if(empty($province['selected'])){
					$parent_id  = $M->where("level_type=2 and id={$city['selected']}")->getField("parent_id");
					$city['options']  	= $M->field('id,parent_id,name,short_name,level_type')->where("parent_id={$parent_id}")->order("id asc")->select();;
					$province['selected'] 	= $parent_id;	
				}else{
					$city['options']  	= $M->field('id,parent_id,name,short_name,level_type')->where("parent_id={$province['selected']}")->order("id asc")->select();	
				}					
			}			
		}
		//区县
		if($level>2){
			$district = array();
			$district['level'] 		= 3;
			$district['area_name'] 	= '区县';
			$district['select_name']= empty($select_name[2]) ? "region{$suffix}[district]" : $select_name[2];
			
			if(empty($selected_value[2])){
				if(!empty($city['selected'])){
					$district['options']  	= $M->field('id,parent_id,name,short_name,level_type')->where("parent_id={$city['selected']}")->order("id asc")->select();			
				}
			}else{
				$district['selected'] 	= $selected_value[2];
				
				if(empty($city['selected'])){
					$parent_id  = $M->where("level_type=3 and id={$district['selected']}")->getField("parent_id");
					$district['options']  	= $M->field('id,parent_id,name,short_name,level_type')->where("parent_id={$parent_id}")->order("id asc")->select();
					$city['selected'] 	= $parent_id;
					//检测城市options 是否存在
					if(empty($city['options'] )){
						$parent_id  = $M->where("level_type=2 and id={$city['selected']}")->getField("parent_id");
						$city['options']  	= $M->field('id,parent_id,name,short_name,level_type')->where("parent_id={$parent_id}")->order("id asc")->select();
						if(empty($province['selected'])){
							$province['selected'] 	= $parent_id;	
						}
					}					
				}else{
					$district['options']  	= $M->field('id,parent_id,name,short_name,level_type')->where("parent_id={$city['selected']}")->order("id asc")->select();	
				}
					
									
			}			
		}
		
		$data[] = $province;
		if(isset($city)){
			$data[] = $city;	
		}
		if(isset($district)){
			$data[] = $district;	
		}		
				
		
		return $data;
		
	}

}