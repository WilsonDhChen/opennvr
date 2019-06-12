<?php

class SystemAction extends BaseAction
{

    public function corestatistics()
    {


        $this->display();
    }

    public function getcoreinfo()
    {
        $info = D('Tool')->mediasrvstatus();
        $this->response("success","",$info);

    }

    public function resorces()
    {
        $this->display();
    }

    public function get_resorces()
    {
        $info = D('Tool')->resorces();
        $this->response("success","",$info);
    }

    public function tooler()
    {

        $this->display();
    }

    public function tooler_post()
    {
        $data['action'] = I('post.action');

        $result = D('Tool')->system_action($data);

        if ($result['return'] == 0) {
            $this->response("success");
        } else {
            $this->response("error",$result['error']);
        }
    }


    public function my_equipment()
    {
		if($_POST){
			$data['app'] = $_POST['app'];
			$data['device_name'] = $_POST['device_name'];
			$data['mode'] = $_POST['mode'];
			$data['domain'] = $_POST['domain'];
			$result = M()->table('config_global_keyvalue')->where("name = 'mode'")->save(array('value'=>$data['mode']));
			$result = M()->table('config_global_keyvalue')->where("name = 'app'")->save(array('value'=>$data['app']));
			$result = M()->table('config_global_keyvalue')->where("name = 'device_name'")->save(array('value'=>$data['device_name']));
			$result = M()->table('config_global_keyvalue')->where("name = 'domain'")->save(array('value'=>$data['domain']));
			$this->response("success","更新成功");
		}else{
			$global_keyvalue = M()->table('config_global_keyvalue')->select();
			foreach($global_keyvalue as $v){
				if($v['name']=='device_name'){
					$this->device_name = $v['value'];
				}
				if($v['name']=='app'){
					$this->app = $v['value'];
				}
				if($v['name']=='mode'){
					$this->mode = $v['value'];
				}
				if($v['name']=='domain'){
					$this->domain = $v['value'];
				}
			}
			$this->display();
		}
	}	

	public function logo(){
		$this->display();
	}
	public function logo_upload(){
		$file = $_FILES['logo_img'];
		$name = $file['name'];
		$type = strtolower(substr($name,strrpos($name,'.')+1));
		if($type!='png'){
			$this->response("error","格式错误请上传png格式");
		}
		if(!is_uploaded_file($file['tmp_name'])){
		  $this->response("error","系统错误");
		}
		$upload_path = APP_ROOT."/oadmin/static/image/";
		if(move_uploaded_file($file['tmp_name'],$upload_path.'logo.png')){
			$this->response("success",'上传成功');
		}else{
			$this->response("error","系统错误");
		}
	}
}

