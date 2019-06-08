<?php

//检测给定数字是否在一个指定范围
function between($src_num,$min_num,$max_num){
	return ($src_num >= $min_num) && ($src_num <= $max_num);
}

//检测一个url是否是一个指定域名的内的
function url_in_domain($url,$domain){
	//检测是否合法URL
	if( !filter_var($url, FILTER_VALIDATE_URL) ) return false;
	//检测域名前加点
	$domain = '.'.ltrim($domain,'.');
	//url中域名和待检测域名对比
	return strtolower(substr(parse_url($url,PHP_URL_HOST),-strlen($domain))) === strtolower($domain);
}

//获取当前页面地址
function location_url(){
	
	if(isset($_SERVER['REQUEST_URI'])){
		$request_uri = $_SERVER['REQUEST_URI'];
	}else{
		if (isset($_SERVER['argv'])){
			$request_uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
		}else{
			$request_uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
		}
	}
	$scheme  = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
	
	return "{$scheme}://{$_SERVER['HTTP_HOST']}{$request_uri}";
}

/**
 * 友好的显示私人隐秘信息
 * $info 需要显示的信息字符串
 * $mode 字符串类型 
 * $symbol 替换显示的符号 默认星号 *
 */
function fspinfo($info,$type,$symbol='*'){
	$modes = array(
			//手机号码 eg: 187*****815
			'cellphone'	=> '/^\d{3}(\d{5})\d{3}$/i',
			//电话号码 eg: 05*******7001
			'telephone'	=> '/^(?:\d{2}(\d{1,2}\-?))?(\d{4})\d{3,4}$/i',
			//身份证号码 eg: 3*****************6
			'idcard'	=> '/^\d(\d{13}|\d{16})\d$/i',
			//邮箱地址 eg: k****@qq.com
			'email'		=> '/^\w(.*?)@.*?$/i',
			//QQ号码 eg: 87*****3
			'qq' 		=> '/^\d{2}(\d{2,8})\d$/i',
			 //中文姓名 eg: *勇
			'name'		=> '/^([\x{4e00}-\x{9fa5}])[\x{4e00}-\x{9fa5}]+$/u'
			);
	$info =(string) trim($info);
	if( isset($modes[$type]) ){
		$pattern = $modes[$type];	
	}else{
		return str_repeat($symbol,mb_strlen($info,'utf-8'));	
	}
	
	preg_match($pattern,$info,$matches,PREG_OFFSET_CAPTURE);
	array_shift($matches);
	foreach($matches as $val){
		$info = substr_replace($info,str_repeat($symbol,mb_strlen($val[0],'utf-8')),$val[1],strlen($val[0]));
	}
	return $info;
}


function timer($mark){
	
	static $timers = array();
	
	if( isset($timers[$mark]) ){
		$diff = floor(microtime(true)*1000) - $timers[$mark];
		unset($timers[$mark]);
		return $diff;
	}else{
		$timers[$mark] = floor(microtime(true)*1000);
	}
	
}


//HTML转义
function html_escape($str,$flags=ENT_QUOTES){
	return htmlspecialchars($str,$flags);
}
//HTML过滤
function html_filter($str,$allowable_tags){
	return strip_tags($str,$allowable_tags);
}

//input过滤
function input_filter($string){
	$string = strip_tags($string);
	$string = str_replace(array("'",'"','&','<','>',';'),'',$string);
	return trim($string);
}


//array_column
if(!function_exists('array_column')){
	   
	function array_column($array,$column_key,$index_key=NULL){
		
 		$result = array();
        foreach($array as $arr){
            if(!is_array($arr)) continue;

            if(is_null($column_key)){
                $value = $arr;
            }else{
                $value = $arr[$column_key];
            }

            if(!is_null($index_key)){
                $key = $arr[$index_key];
                $result[$key] = $value;
            }else{
                $result[] = $value;
            }

        }

        return $result;		
		
	}
	   
}

//返回二维数组中 指定键值的 一项。
function array_select($key,$val,$array){
	if(empty($array) || !is_array($array)) return array();
	
	foreach($array as $item){
		if($item[$key]==$val){
			return $item;
			break;	
		}
	}
	
}

//整理一个二维数组，使用第二维中指定键的值，作为一维的键
function array_related($key,$array){
	
	if(empty($array) || !is_array($array)) return $array;
	
	$new_array = array();
	foreach($array as $item){
		$new_item = $item;
		unset($new_item[$key]);
		$new_array[$item[$key]] = $new_item;
	}
	
	return $new_array;
	
}

//获取数组以指定字符开头的数组元素 返回一个新的符合条件数组
function array_prefix($prefix,$array){
	$filter = array();
	foreach($array as $key=>$val){
		if( strpos($key,$prefix)===0 ){
			$filter[$key] = $val;
		}
	}
	
	return $filter;
}

//双引号或单引号转换成实体
function quotes_entities($string, $specify = 0)
{

    switch ($specify) {
        //转换 单双引号
        case 0 :
            return str_replace(array("'", '"'), array('&#x27;', '&#x22;'), $string);
        break;
        //只转换单引号
        case 1 :
            return str_replace("'", '&#x27;', $string);
            break;
        //只转换双引号
        case 2 :
            return str_replace('"', '&#x22;', $string);
            break;
    }

    return $string;

}

//无限极分类整理
function get_tree_node($all_nodes,$node_id_name='node_id',$parent_id_name='parent_id',$sub_node_name = 'nodes'){
	
    $new_nodes = array(); 
    $map_nodes  = array();
     
    foreach ($all_nodes as $node) {
		$map_nodes[$node[$node_id_name]] = $node;        
    }

    foreach ($all_nodes as $node) {
        if (isset($map_nodes[$node[$parent_id_name]])) {
            $map_nodes[$node[$parent_id_name]][$sub_node_name][] = &$map_nodes[$node[$node_id_name]];
        } else {
			$new_nodes[] = &$map_nodes[$node[$node_id_name]];
        }
    }
    unset($map_nodes);
    return $new_nodes;
}


