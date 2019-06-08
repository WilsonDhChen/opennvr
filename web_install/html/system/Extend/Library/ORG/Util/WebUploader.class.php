<?php
/**
 +-------------------------------------------------------------
 | WebUploader 文件上传类 
 +-------------------------------------------------------------
 | @auther		: Mr.Kin
 | @date		: 2014-12-02
 +-------------------------------------------------------------
 */
 
 
class WebUploader{
	
	//上传配置
	private $config = array(
							//上传文件保存路径
							'save_path'			=> NULL,
							//最大尺寸文件限制(默认不限制) 可使用单位(KB,MB,GB,TB),不带单位 纯数字表示Bytes
							'max_size'			=> 0,
							//最小尺寸文件限制(默认不限制)
							'min_size'			=> 0,
							//允许上传文件的扩展名(默认不限制文件扩展名)  可以使用数组 array('jpg','jpeg','png') 或以逗号分隔的字符串 'jpg,jpeg,png';
							'ext_type' 			=> NULL,
							//允许上传文件的MimeType(默认不限制文件MimeType)  可以使用数组 array('image/jpeg','image/png','image/gif') 或字符串 'image/jpeg,image/png,image/gif';
							'mime_type' 		=> NULL,
							//debug
							'debug'  			=> NULL,
							
							//以下配置用于多文件上传
							//是否忽略上传的多文件中的空文件
							'ignore_empty'			=> true,
							//是否忽略上传的多文件中的发生错误文件 如果true遇到错误继续接收下一个文件，如果false直接return false ，getError()可获取错误信息
							'ignore_error'			=> true,
							
	);
	
	//文件上传中可能会出现的错误, 提示项为array()表示debug模式下提示语为数组第一项，非debug下为第二项。debug可调用 $class->config('debug',true) 方法设置.
	private $error_list = array(
									 1	=>	array('上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值' , '上传的文件尺寸太大#1'),
									 2	=>	array('上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值'	 ,   '上传的文件尺寸太大#2'),
									 3	=>  array('文件只有部分被上传' , '文件上传中断'),
									 4  =>	array('没有获取到上传的文件' , '服务器没有检测到上传的文件'),
									 6	=>	array('找不到临时文件夹' , '服务器故障#1'),
									 7	=>	array('文件写入失败' , '服务器故障#2'),
									 8	=>	array('上传的文件尺寸超过设定值' , '上传的文件尺寸太大#3'),
									 9	=>	array('上传的文件尺寸低于设定值' , '上传的文件尺寸太小'),
									 10 =>	array('上传文件保存路径权限不足' , '服务器故障#4'),
									 11 =>	array('上传文件保存路径没有设置' , '服务器故障#5'),
									 12 =>	array('文件扩展名不在设定值范围内' , '上传的文件类型不在允许的范围内#1'),
									 13 =>	array('文件MimeType不在设定值范围内' , '上传的文件类型不在允许的范围内#2'),
									 14 =>	array('文件保存时发生错误(move_uploaded_file移动到新位置时)' , '服务器故障#6'),
									 15 =>	array('检测到指定上传的文件是多文件，请使用multiUpload()接收' , '服务器故障#7'),
									 16 =>	array('多文件上传 $inputs参数类型错误，仅可为 空,NULL,字符串,数组' , '服务器故障#8'),
									 
	);
	
	//上次错误信息
	private $error_info = NULL;
	
	//已经上传接收过的文件字段名
	private $uploaded = array();

	
	public function __construct($config=NULL){
		
		if( !is_null($config) ){
			$this->config($config);
		}
		
	}
	
	//设置或获取上传配置
	public function config($key=NULL,$val=NULL){
		$args_num = func_num_args();
		//如果无参数传递返回全部上传参数
		if($args_num == 0){
			return $this->config;
			
		}else if($args_num==1){
		//一个参数情况
			//如果是数组 则表示赋值
			if(is_array($key)){
				$this->config = array_merge($this->config,array_change_key_case($key));
			}else if(is_string($key)){
			//如果是字符串表示获取此键值的配置值
				return $this->config[strtolower($key)];
			}else{
			//其他类型无效，返回NULL
				return NULL;	
			}	
			
		}else{
		//多于1个参数 只处理前两个参数 表示赋值
			$this->config[strtolower($key)] = $val;
		}
			
	}	 
	
	//上传一个文件
	//@$input {string} 文件域名称 [可选] 若为空上传第一个文件域文件
	//@$name {string} 保存的文件名称(不含扩展名)[可选] 默认 %RAND%变量 随机生成文件名，如想使用原文件名称请传递 %NAME%变量
	//使用%RAND%,%NAME% 变量时候 可加自定义字符串  如：固定前缀的 img_%RAND% 或是原名称加随机名 %NAME%_%RAND%
	//@$ext {string}  保存的文件扩展名 默认%EXT% 使用原扩展名[可选]
	//可使用变量有  %NAME%文件原名称 %EXT%文件原扩展名 %RAND%随机32位字符
	public function upload($input=NULL,$name='%RAND%',$ext='%EXT%'){
		
		//检测input是否无效为空 如果为空则接收第一个文件域
		if( $this->invalid($input) ){
			//取得当前FILES第一个文件
			$file 	= current($_FILES);
			//第一个文件对应的input
			$input 	= key($_FILES);
		}else if(is_array($input)){
		//直接文件数组传递 为多文件上传预留接口
			$file = $input;
		}else{
			$file = $_FILES[$input];	
		}
		
		//无效的文件
		if( !$this->valid_file($file) ){
			$this->setError(4);	
			return false;
		};
		
		//如果检测到是多文件上传 抛出错误 应该使用 multiUpload() 接收
		if( count($file,1)>5 ){
			$this->setError(15);	
			return false;
		}
		
		//上传中是否出错
		if($file['error']>0){
			$this->setError($file['error']);
			return false;	
		}
		
		//文件验证
		if( $this->verify($file) ){
			$this->uploaded[] = $input;
			//验证通过 保存文件 返回文件信息
			return $this->save($file,$name,$ext);
		}else{
			//验证失败 返回 false
			return false;	
		}
		
		
	}


	//上传多个文件
	//$inputs 如果为空则接收所有未接收过文件
	//$inputs 可以是数组或是逗号分隔的字符串
	public function multiUpload($inputs=NULL,$name='%RAND%',$ext='%EXT%'){
		
		//如果inputs传值无效或是没有传值 则接收所有文件
		if( $this->invalid($inputs) ){
			$inputs = array_keys($_FILES);
		}else if(is_string($inputs)){
		//以逗号分隔的文件域名称
			$inputs = explode(',',$inputs);	
		}
		//参数类型错误
		if( !is_array($inputs) ){
			$this->setError(16);
			return false;	
		}
		//排除已经上传过的
		$inputs = array_diff($inputs,$this->uploaded);
		//开始接收多文件
		$result = array();
		foreach($inputs as $input){
						
			//数组类型多文件检测 input[] 形式			
			if(count($_FILES[$input],1)>5){
				$files = $this->rearrange($_FILES[$input]);
				$result[$input] = array();
				foreach($files as $file){
					//无效空文件处理
					if( !$this->valid_file($file) ){
						if( $this->config['ignore_empty'] ){
							continue;
						}else{
							$this->setError(4);	
							return false;
						}
					}
					//上传					
					$upload_result = $this->upload($file,$name,$ext);
					//遇错处理
					if($upload_result){
						$result[$input][] = $upload_result;
					}else{
						if( $this->config['ignore_error'] )	{
							$this->error_info = NULL;
							$result[$input][] = false;		
						}else{
							return false;	
						}
					}
				}
				//
				$this->uploaded[] = $input;	
				
			}else{
				//无效空文件处理
				if( !$this->valid_file($_FILES[$input]) ){
					if( $this->config['ignore_empty'] ){
						continue;	
					}else{
						$this->setError(4);	
						return false;
					}
				}
				//上传
				$upload_result = $this->upload($input,$name,$ext);
				//遇错处理
				if($upload_result){
					$result[$input]  = $upload_result;
				}else{
					if( $this->config['ignore_error'] )	{
						$this->error_info = NULL;
						$result[$input] = false;		
					}else{
						return false;	
					}
				}				
			}
			
				
		}
		
		return $result;
			  	
	}

	
	//获取上一次上传的错误信息
	public function getError(){
		return $this->error_info;	
	}
	
	
	//设置错误信息
	private function setError($index){
		
		//获取当前索引错误信息
		$error_info = $this->error_list[$index];
		//如果错误信息是数组 
		if(is_array($error_info)){
			//检测debug是否开启
			if($this->config['debug']===NULL){
				if( @ini_get('display_errors')==1 ){
					$this->config['debug'] = true;		
				}else{
					$this->config['debug'] = false;			
				}
			}
			//确定错误信息类型
			$type = $this->config['debug']== true ? 0 : 1;
			//存储错误信息
			$this->error_info = $error_info[$type] ;
		}else if(is_string($error_info)){
		//如果错误信息是string 则直接返回
			$this->error_info = $error_info ;
		}
		
		
	}
	
	//验证文件上传
	private function verify($file){
		
		//最大尺寸验证
		if($this->config['max_size']!=0){
			$max_size = $this->format_size($this->config['max_size']);
			if($file['size']>$max_size){
				$this->setError(8);
				return false;	
			}
		}
		//最小尺寸验证
		if($this->config['min_size']!=0){
			$min_size = $this->format_size($this->config['min_size']);
			if($file['size']<$min_size){
				$this->setError(9);
				return false;
			}
		}
		//验证文件扩展名
		if( !empty($this->config['ext_type']) ){
			$ext_type = is_string($this->config['ext_type']) ? explode(',',$this->config['ext_type']) : (array) $this->config['ext_type'];
			//去除扩展名带有.前缀的
			array_walk($ext_type,array($this,'ltrim_dot'));
			if( !in_array( pathinfo( $file['name'] , PATHINFO_EXTENSION ) , $ext_type ) ){
				$this->setError(12);
				return false;					
			}
		}	
		
		//验证文件MimeType
		if( !empty($this->config['mime_type']) ){
			$mime_type = is_string($this->config['mime_type']) ? explode(',',$this->config['mime_type']) : (array) $this->config['mime_type'];
			//处理ie浏览器中 图片怪癖MimeType值
			if( in_array('image/jpeg',$mime_type)) $mime_type[] = 'image/pjpeg';
			if( in_array('image/png' ,$mime_type)) $mime_type[] = 'image/x-png';
			if(!in_array( $file['type'] , $mime_type) ){
				$this->setError(13);
				return false;					
			}
		}
		
		return true;					
		
			
	}
	
	//是否是有效的文件域(有文件的)
	private function valid_file($input){
		return !empty($input['name']) && !empty($input['type']) && !empty($input['tmp_name']) && is_numeric($input['error']) && is_numeric($input['size']);
	}
	
	//保存文件
	private function save($file,$name,$ext){
		//文件保存路径并去除末尾斜杠
		$file_path = rtrim($this->config['save_path'],'\/');
		//检测是否设置文件保存路径
		if(empty($file_path)){
			$this->setError(11);
			return false;	
		}
		//检测路径是否存在如果不存在则创建
		if( !is_dir($file_path) && !mkdir($file_path,0755,true) ){
			$this->setError(10);
			return false;			
		}
		//获取保存文件名(含扩展名)
		$file_name = $this->get_file_name($file['name'],$name,$ext);
		
		//保存文件
		if(@move_uploaded_file($file['tmp_name'],$file_path.'/'.$file_name)){
			$file['save_path'] = $file_path;
			$file['new_name']  = $file_name;
			return $file;
		}else{
			$this->setError(14);
			return false;	
		}
		
			
	}	
	
	
	//获取保存文件名(含扩展名)
	private function get_file_name($file_name,$name,$ext){
		//解析完整文件名
		$file_info 	= pathinfo($file_name);
		//文件名值
		$file_val 	= array($file_info['filename'],$file_info['extension'],$this->rand_file_name());
		//文件名变量
		$file_var 	= array('%NAME%','%EXT%','%RAND%');
		//指定的新文件名如果是NULL,false,true或是空字符串 将使用随机文件名
		$name 	= $this->invalid($name) ? '%RAND%' : $name;
		//新文件名 
		$name 	= str_replace($file_var,$file_val,$name);
		//新扩展名
		$ext 	= str_replace($file_var,$file_val,$ext);
		//最终完整文件名称(如果扩展名是NULL,false,true或是空字符串 将直接使用文件名)
		$file_name = $this->invalid($ext) ?  $name : $name.'.'.$ext;
		
		return $file_name;
		
	}
	
	//生成随机文件名
	private function rand_file_name(){
		return md5(uniqid(rand(), true).rand(100000,999999));	
	}

	//整理多文件上传数组 
	private function rearrange( $arr ){
	     foreach( $arr as $key => $all ){
	         foreach( $all as $i => $val ){
	             $new[$i][$key] = $val;    
	        }    
	    }
	     return $new;
	 }	 
	 
	//是否是无效的值
	private function invalid($val){
		
		return ($val==='' || $val===NULL || $val===false || $val===true);
		
	}
	
	//(引用&)去除字符串左边所有的dot.
	private function ltrim_dot(&$string){
	
		$string = ltrim($string,'.');
		
	}		
		
	
	//单位转换
	private function format_size($size){
		//如果是数字 返回原值 单位 Bytes
		if( is_numeric($size) ) return $size;
		//获取单位
		$unit = strtoupper(substr($size,-2,2));
		//获取数值
		$size = rtrim($size,$unit);
		//Bytes尺寸
		$bytes = 0;
		switch($unit){
			case 'KB' : $bytes = $size * pow(2,10); break;
			case 'MB' : $bytes = $size * pow(2,20); break;
			case 'GB' : $bytes = $size * pow(2,30); break;
			case 'TB' : $bytes = $size * pow(2,40); break;
		}
		return $bytes;
	}	

}