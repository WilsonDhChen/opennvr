<?php

//扩展的密码md5
function pwd_md5($password){
	return md5($password);
}

//系统表单模型实例
function form($form){
	static $FormQuery;
	if(empty($FormQuery)){
		$FormQuery = new FormQueryModel();
	}
	
	return $FormQuery->setForm($form);
}


//grid 排序
function grid_sort($name,$field){
	$url = location_url();
	$query = parse_url($url,PHP_URL_QUERY);
	$sort = I('get.sort','','html_escape');
	if($sort){
		$order = explode('.',$sort);
		if($order[1]=='desc'){
			$title = '降序排序';
			$url = str_replace("sort={$_GET['sort']}","sort={$field}.asc",$url);
		}else{
			$title = '升序排序';
			$url = str_replace("sort={$_GET['sort']}","sort={$field}.desc",$url);
		}
	}else{
		if($query){
			$url .="&sort={$field}.desc";
		}else{
			$url .="?sort={$field}.desc";
		}
		$title = '点击按降序排序';
	}
	
	
	
	return '<a href="'.$url.'" class="grid-sort" title="'.$title.'">'.$name.'</a>';
}

//权限验证
function power($navi){
	
	static $passed = array();
	
	$navi = strtolower($navi);
	if(strpos($navi,'.')){
		list($module,$action) = explode('.',$navi);	
	}else{
		$module = strtolower(MODULE_NAME);
		$action = $navi;	
	}
	$navi = $module.'.'.$action;
	if(isset($passed[$navi])){
		return $passed[$navi];	
	}
	$permit = $GLOBALS['page_power_data']['permit'];
	$staff = $GLOBALS['page_power_data']['staff'];
	foreach($permit as $val){
		
		if($val['module']==$module && in_array($action,explode(',',$val['action']))){
			$passed[$navi] = true;
			return true;
			break;	
		}
		
	}
	
	$passed[$navi] = false;
	return false;
		
}

//共享权限验证
function spower($navi){
	
	static $passed = array();
	
	$navi = strtolower($navi);
	if(strpos($navi,'.')){
		list($module,$action) = explode('.',$navi);	
	}else{
		$module = strtolower(MODULE_NAME);
		$action = $navi;	
	}	
	
	$navi = $module.'.'.$action;
	if(isset( $passed[$navi] )){
		return $passed[$navi];	
	}	
	
	$staff = $GLOBALS['page_power_data']['staff'];
	
	$passed[$navi] = D('Navi')->getSPermit($staff['roles'],$staff,$module,$action);
	
	
	return $passed[$navi]; 
	
}

//获取系统配置
function config($identifier){
	
	static $Config = NULL;
	if(empty($Config)){
		$Config = D('Config');
	}
	$Config->identifier = $identifier;
	return $Config;
}



//获取指定员工信息
function staff($staff_id,$filed){
	
	if(stripos($filed,',')===false){
		return M('sys_staff')->where("staff_id={$staff_id}")->getField($filed);
	}else{
		return M('sys_staff')->field($filed)->where("staff_id={$staff_id}")->find();	
	}

}

function model($name){
    $model_name = $name.'Model';
    return new $model_name();
}


function get_tpl_var($name){
	return C('TMPL_PARSE_STRING.'.$name);
}




