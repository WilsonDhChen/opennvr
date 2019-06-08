<?php

/**
 * URL 加密类
 * author : Kin
 * 编码加密 AuthUrl::encode($url,$key); 
 * 解码验证 AuthUrl::verify($url,$key); 
 * 
 */

class AuthUrl {
	
	//默认全局加密key
	static private $key = 'so6q0s825n1pn4s76pns75q54r0p6s4p';

	
	//设置全局加密key
	static public function setKey($key){
		
		if( !empty($key) ){
			self::$key = $key;	
		}
		
	}

	//创建加密后的URL
	static public function create($url,$key=''){
		
		//解析URL结构成数组
		$url_item = parse_url($url);
		//获取url gene
		$url_gene = self::get_url_gene($url_item['query'],$key);
		//重新构造
		$url_item['scheme'] 	= empty($url_item['scheme'])   ? '' : $url_item['scheme'].'://';
		$url_item['fragment']   = empty($url_item['fragment']) ? '' : '#'.$url_item['fragment'];
		$url_item['query'] 		= "?{$url_item['query']}&url_gene={$url_gene}";
		return implode($url_item);
	  
	}
	
	
	//验证当前URL
	static public function verify($key=''){
		//当前URL真实的 url gene
		$url_gene_real = md5(self::get_url_gene($_SERVER['QUERY_STRING'],$key));
		//接收到的 url gene
		$url_gene_user = isset($_GET['url_gene']) ? md5($_GET['url_gene']) : NULL;
		//
		return $url_gene_real == $url_gene_user;
		
	}
	
	
	//
	static private function get_url_gene($query,$key){
		
		//URL GET query 转为数组
		parse_str($query,$url_query_item);
		unset($url_query_item['url_gene']);
		//按GET键排序数组
		ksort($url_query_item);
		//获取加密key
		$key = empty($key) ? self::$key : $key;
		//构建URL唯一性因子
		$url_factor = http_build_query($url_query_item).'&'.$key;
		//return url_gene
		return  strrev(str_rot13(md5($url_factor)));		
	}
	
	
}


