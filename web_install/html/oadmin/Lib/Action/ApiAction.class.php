<?php


class ApiAction extends BaseAction{



	public function region(){

        if (!headers_sent()) {
            header('Content-type: application/json');
        }

		$id = I('get.id',0,'intval');
		if(empty($id)){
			echo '[]';exit;
		}
		$region = M('com_region')->field('id,parent_id,name,short_name,level_type')->where("parent_id={$id}")->order("id asc")->select();
		if($region){
			if(version_compare('5.4',PHP_VERSION)==1){
				echo json_encode($region);exit;	
			}else{
				echo json_encode($region,JSON_UNESCAPED_UNICODE);exit;
			}
			
		}else{
			echo '[]';exit;	
		}
			
	}
	
	
	public function select(){
		
		header('Content-type: application/json');
		
		$id = I('post.id',0,'intval');
		if(empty($id)){
			echo '[]';exit;
		}
		
		$table = I('post.table');
		$node_id = I('post.node_id');
		$node_name = I('post.node_name');
		$parent_id = I('post.parent_id');
		
		$where = $parent_id.'='.$id;

		if(!empty($_POST['where'])){
			$where.=' AND ('.I('post.where').')';
		}
		if(empty($_POST['order'])){
			$order = I('post.node_id').' asc';
		}else{
			$order = I('post.order');
		}		
		$data = M($table)->field("{$node_id},{$node_name},{$parent_id}")->where($where)->order($order)->select();
		
		if($data){
			if(version_compare('5.4',PHP_VERSION)==1){
				echo json_encode($data);	
			}else{
				echo json_encode($data,JSON_UNESCAPED_UNICODE);
			}
			exit;
		}else{
			echo '[]';
			exit;	
		}			
	}
	
	public function witaform(){
		

        $type = I('post.type');

		$rule = trim($_POST['rule'],',');

        //特殊类型映射
        $maps = array(

            'datepicker:date'       =>'date',
            'datepicker:datetime'   =>'datetime',
            'ueditor'               =>'textarea',
        );

        if (isset($maps[$type])) {
            $type = $maps[$type];
        }

        /**
         * 允许的类型
         * text textarea url email number  date time datetime tel search select checkbox radio file hidden password
         */

        $this->assign('type', $type);
        $this->assign('rule', $rule);
        $this->assign('backslash', chr(92));


		$this->display();
	}

    public function camera_list()
    {
        $k = I('get.k');
        if ($k !== '97ec25cddaca7a2713c3ed420337da79')
            exit(json_encode(array(
                'code' => 1,
                'msg' => 'access denied',
            )));

        $pg = I('get.pg', 1);
        $pz = I('get.pz', 10);
        $offset = $pz*($pg-1);

        $where = array(
            'sTypeName' => '摄像机',
            'sStatus' => 'ON',
        );
        $Mdl = M();
        $list = $Mdl->table('ext_gb28181_catalog')->field('sChid,sName,sUpdateTime')->where($where)->limit($offset, $pz)->select();
        exit(json_encode(array(
            'code' => 0,
            'data' => $list,
        )));
    }
	
}
