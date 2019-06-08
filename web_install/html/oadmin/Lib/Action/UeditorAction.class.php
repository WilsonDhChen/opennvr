<?php

class UeditorAction extends BaseAction{
	
	private $ue_config;
	
	public function _init(){
		
		$this->ue_config = require(CONF_PATH.'/ueditor.php');
		
	}
	
	//入口
	public function index(){
		
		$action = I('get.action','config','input_filter');
		R(MODULE_NAME.'/'.$action);

	}
	
	//编辑器获取配置
	public function config(){
		
		echo json_encode($this->ue_config);

	}
	
	
	public function uploadimage(){
		
		$upload_config = array('max_size' => $this->ue_config['imageMaxSize'],	'save_path'	=>$this->ue_config['imagePathFormat'].'/'.date('Y/m') , 'ext_type'=>$this->ue_config['imageAllowFiles']);
		$WebUploader = new WebUploader($upload_config);
		$result =  $WebUploader->upload($this->ue_config['imageFieldName']);
		if($result){
			
			$this->image_compress($result,$this->ue_config['imageCompressEnable'],$this->ue_config['imageCompressBorder']);
			//
			$response = array();
			$response['state'] 		= 'SUCCESS';
			$response['url']   		= ltrim($result['save_path'].'/'.$result['new_name'],'.');
			$response['title'] 		= $result['name'];
			$response['original'] 	= $result['name'];
			$response['type'] 		= pathinfo($result['new_name'],PATHINFO_EXTENSION);
			$response['size'] 		= $result['size'];
			$this->UEResponse($response);
		}else{
			$this->UEResponse(array('state'=>$WebUploader->getError()));	
		}
	
	}
	
	public function uploadfile(){
		
		$upload_config = array('max_size' => $this->ue_config['fileMaxSize'],	'save_path'	=>$this->ue_config['filePathFormat'].'/'.date('Y/m') , 'ext_type'=>$this->ue_config['fileAllowFiles']);
		$WebUploader = new WebUploader($upload_config);
		$result =  $WebUploader->upload($this->ue_config['fileFieldName']);
		if($result){
			$response = array();
			$response['state'] 		= 'SUCCESS';
			$response['url']   		= ltrim($result['save_path'].'/'.$result['new_name'],'.');
			$response['title'] 		= $result['name'];
			$response['original'] 	= $result['name'];
			$response['type'] 		= pathinfo($result['new_name'],PATHINFO_EXTENSION);
			$response['size'] 		= $result['size'];
			$this->UEResponse($response);
		}else{
			$this->UEResponse(array('state'=>$WebUploader->getError()));	
		}
	
	}
	
	public function listimage(){
		
        $allowFiles = $this->ue_config['imageManagerAllowFiles'];
        $listSize = $this->ue_config['imageManagerListSize'];
        $path = $this->ue_config['imageManagerListPath'];	
		
		$allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);
		
		/* 获取参数 */
		$size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
		$start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
		$end = $start + $size;
		
		/* 获取文件列表 */
		//$path = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == "/" ? "":"/") . $path;
		$files = $this->getfiles($path, $allowFiles);
		if (!count($files)) {
		    return json_encode(array(
		        "state" => "no match file",
		        "list" => array(),
		        "start" => $start,
		        "total" => count($files)
		    ));
		}
		
		/* 获取指定范围的列表 */
		$len = count($files);
		for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
		    $list[] = $files[$i];
		}
		
		/* 返回数据 */
		$this->UEResponse(array(
		    "state" => "SUCCESS",
		    "list" => $list,
		    "start" => $start,
		    "total" => count($files)
		));		
		
	}
	
	
	public function listfile(){
		
        $allowFiles = $this->ue_config['fileManagerAllowFiles'];
        $listSize = $this->ue_config['fileManagerListSize'];
        $path = $this->ue_config['fileManagerListPath'];	
		
		$allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);
		
		/* 获取参数 */
		$size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
		$start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
		$end = $start + $size;
		
		/* 获取文件列表 */
		//$path = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == "/" ? "":"/") . $path;
		$files = $this->getfiles($path, $allowFiles);
		if (!count($files)) {
		    return json_encode(array(
		        "state" => "no match file",
		        "list" => array(),
		        "start" => $start,
		        "total" => count($files)
		    ));
		}
		
		/* 获取指定范围的列表 */
		$len = count($files);
		for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
		    $list[] = $files[$i];
		}
		
		/* 返回数据 */
		$this->UEResponse(array(
		    "state" => "SUCCESS",
		    "list" => $list,
		    "start" => $start,
		    "total" => count($files)
		));		
		
	}		
	
	
	//
	public function UEResponse($array){
	
		echo json_encode($array);
		exit;
			
	}
	
	//
	private function image_compress($img,$enable,$max){
		$file = $img['save_path'].'/'.$img['new_name'];
		if(!$enable || !is_file($file)){
			return false;	
		}
		$width = current(getimagesize($file));
		if($width<$max){
			return false;	
		}
		$Images = new Images();
		$Images->loadFile($file)->resize($max,$max)->save($file);
		
	}
	
	/**
	 * 遍历获取目录下的指定类型的文件
	 * @param $path
	 * @param array $files
	 * @return array
	 */
	private function getfiles($path, $allowFiles, &$files = array()){
	    
		if (!is_dir($path)) return NULL;
	    if(substr($path, strlen($path) - 1) != '/') $path .= '/';
	    $handle = opendir($path);
	    while (false !== ($file = readdir($handle))) {
	        if ($file != '.' && $file != '..') {
	            $path2 = $path . $file;
	            if (is_dir($path2)) {
	                $this->getfiles($path2, $allowFiles, $files);
	            } else {
	                if (preg_match("/\.(".$allowFiles.")$/i", $file)) {
	                    $files[] = array(
	                        'url'=> ltrim($path,'.').$file,
	                        'mtime'=> filemtime($path2)
	                    );
	                }
	            }
	        }
	    }
	    return $files;
	}	


}
