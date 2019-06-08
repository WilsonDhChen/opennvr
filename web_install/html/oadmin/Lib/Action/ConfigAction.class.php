<?php

class ConfigAction extends BaseAction{
	

	public function system(){
	
		$this->context_menu = D('Config')->getContextMenu();
		
		$this->display();
	}
	
	public function common(){
		
		$this->context_menu = D('Config')->getContextMenu();
				
		$this->display();
	}	
	
	public function load(){
		$id = I('post.id',0,'intval');
		
		if(empty($id)){
			switch(I('post.cate','','trim')){
			  case 'system' : $id = 1;break;
			  case 'common' : $id = 2;break;
			  default: $this->response('error','参数丢失');
			}
		}
		
		$M = M('sys_config');

		$list = $M->query("select config_id as id,name,if((select count(1) from __PREFIX__sys_config where parent_id=a.config_id )>0,'true','false') as isParent from __PREFIX__sys_config as a where parent_id={$id} order by sort desc,config_id asc");
		
		exit(json_encode($list));
	}
	
	public function get_config(){
		
		$config_id = I('post.config_id',0,'intval');
		if(empty($config_id)){
			$this->response('error','参数丢失');	
		}
		
		$M = M('sys_config');
		$result = $M->alias('a')->field("a.*,if(a.parent_id=0,'顶级栏目',(select name from ".__PREFIX__."sys_config where config_id=a.parent_id)) as parent_name")->where("config_id={$config_id}")->find();
		if($result){
			if(!empty($result['attrs'])){
				$result['attrs'] = json_decode($result['attrs']);
			}
			$this->response('success','获取成功',$result);	
		}else{
			$this->response('error','网络故障 请重试');	
		}
			
	}
	
	public function system_add_post(){
		
		$Config = D('Config');
		
		$data = array();
		$data['parent_id'] = I('post.parent_id',1,'intval'); 
		if(isset($_POST['identifier'])){
			$data['identifier'] = I('post.identifier','','html_escape');
			if(!empty($data['identifier']) && $Config->checkIdentifier($data['identifier'])){
				$this->response('error','配置标识已经存在');
			}
			if(!empty($data['identifier']) && is_numeric($data['identifier'])){
				$this->response('error','配置标识不允许为纯数字');
			}
		}
		$data['name'] = I('post.name','','html_escape'); 
		if(empty($data['name'])){
			$this->response('error','配置名称不能为空');
		}
		$data['sort'] = I('post.sort',0,'intval'); 

		$data['insert_time'] = time();
		
		$data['attrs'] = $Config->parseAttrs($_POST['attrs_key'],$_POST['attrs_val']);
		
		$result = $Config->add($data);	

		if($result){
			$this->response('success','操作成功');
		}else{
			$this->response('error','网络故障，操作失败');	
		}
		
	}
	
	public function system_edit_post(){
		
		$Config = D('Config');
		
		$config_id = I('post.config_id',0,'intval');
		
		if(empty($config_id)){
			$this->response('error','参数丢失');
		}	
		$data = array();
		$data['parent_id'] = I('post.parent_id',1,'intval'); 
		if(isset($_POST['identifier'])){
			$data['identifier'] = I('post.identifier','','html_escape');
			if(!empty($data['identifier']) && $Config->where("identifier='{$data['identifier']}' and config_id!={$config_id}")->count()){
				$this->response('error','配置标识已经存在');
			}
			if(!empty($data['identifier']) && is_numeric($data['identifier'])){
				$this->response('error','配置标识不允许为纯数字');
			}			
		}		
		$data['name'] = I('post.name','','html_escape'); 
		if(empty($data['name'])){
			$this->response('error','配置名称不能为空');
		}
		$data['sort'] = I('post.sort',0,'intval'); 
		
		$data['attrs'] = $Config->parseAttrs($_POST['attrs_key'],$_POST['attrs_val']);

		$result = $Config->where("config_id={$config_id}")->save($data) === false ? false : true;

		if($result){
			$this->response('success','操作成功');
		}else{
			$this->response('error','网络故障，操作失败');	
		}
		
	}	
	
	
	public function common_add_post(){
		
		$data = array();
		$data['parent_id'] = I('post.parent_id',1,'intval'); 
		
		$Config = D('Config');
		
		if(isset($_POST['identifier'])){
			$data['identifier'] = I('post.identifier','','html_escape');
			if(!empty($data['identifier']) && $Config->checkIdentifier($data['identifier'])){
				$this->response('error','配置标识已经存在');
			}
			if(!empty($data['identifier']) && is_numeric($data['identifier'])){
				$this->response('error','配置标识不允许为纯数字');
			}			
		}		
		
		$data['name'] = I('post.name','','html_escape'); 
		if(empty($data['name'])){
			$this->response('error','配置名称不能为空');
		}
		$data['sort'] = I('post.sort',0,'intval'); 

		$data['insert_time'] = time();
		
		
		$data['attrs'] = $Config->parseAttrs($_POST['attrs_key'],$_POST['attrs_val']);
		
		$M = M('sys_config');

		$result = $M->add($data);	

		if($result){
			$this->response('success','操作成功');
		}else{
			$this->response('error','网络故障，操作失败');	
		}
		
	}
	
	public function common_edit_post(){
		
		$config_id = I('post.config_id',0,'intval');
		
		if(empty($config_id)){
			$this->response('error','参数丢失');
		}	
		
		$Config = D('Config');
		
		$data = array();
		$data['parent_id'] = I('post.parent_id',1,'intval'); 
		if(isset($_POST['identifier'])){
			$data['identifier'] = I('post.identifier','','html_escape');
			if(!empty($data['identifier']) && $Config->where("identifier='{$data['identifier']}' and config_id!={$config_id}")->count()){
				$this->response('error','配置标识已经存在');
			}
			if(!empty($data['identifier']) && is_numeric($data['identifier'])){
				$this->response('error','配置标识不允许为纯数字');
			}			
		}		
		$data['name'] = I('post.name','','html_escape'); 
		if(empty($data['name'])){
			$this->response('error','配置名称不能为空');
		}
		$data['sort'] = I('post.sort',0,'intval'); 
		
		$data['attrs'] = $Config->parseAttrs($_POST['attrs_key'],$_POST['attrs_val']);
		
		$M = M('sys_config');

		$result = $M->where("config_id={$config_id}")->save($data) === false ? false : true;

		if($result){
			$this->response('success','操作成功');
		}else{
			$this->response('error','网络故障，操作失败');	
		}
		
	}	
	

	
	public function delete_post(){
		
		$config_id = I('post.config_id',0,'intval');
		if(empty($config_id)){
			$this->response('error','参数丢失');	
		}
		$Config = D('Config');
		$Config->configDelete($config_id);
		$result = $Config->where("config_id={$config_id}")->delete()===false ? false : true;
		
		if($result){
			$this->response('success','删除成功');
		}else{
			$this->response('error','网络故障，删除失败');	
		}

	}


	public function tonavi(){
		$config_id = I('get.id',0,'int');
		if(empty($config_id)){
			$this->response('error','参数丢失');	
		}
		
		$config = D('Config')->find($config_id);
		if(empty($config)){
			$this->response('error','该配置不存在或是已被删除');	
		}
		if(empty($config['identifier'])){
			$this->response('error','没有填写配置标识,无法进行此操作');	
		}
		
		$config['attrs'] = json_decode($config['attrs'],true);
		
		//POST request
		if(IS_POST && I('post.submit')){
			$method = 'tonavi_'.I('post.tonavi_type');
			if(method_exists($this,$method)){
				$this->$method($config);
			}else{
				$this->response('error','不存在的表单操作类型');		
			}
			exit;	
		}
		
		//GET request
		$this->assign('config',$config);
		$this->assign('attrs',$config['attrs']);
		
		if( strpos($config['identifier'],'@') ){
			$this->response('error','此类型生成栏目尚未启用');
			//自定义表单的，还没开发完成，尚未启用
			//$this->display('tonavi_form');
		}else{
			
			//检测是否已经生成过栏目
			$navi = M('sys_navi')->where("module='config' AND action='{$config['identifier']}__index'")->find();
			//如果生成过 读取栏目父级、子级数据
			//栏目所有父级navi_id (包含自身navi_id)
			$parentsid = array();
			//栏目所有子级的权限action
			$navi_actions = array();
			if(!empty($navi)){
				$parentsid 	= $this->tonavi_get_parentsid($navi['navi_id']);
				$navi_actions = $this->tonavi_get_actions($navi['navi_id'],$config['identifier']);
			}
			
			$types = array('sets','node','opts');	
			$type = I('get.type');
			if(empty($type) || !in_array($type,$types)){
				$type_val = empty($config['attrs']['config_type'])?$types[0]:$config['attrs']['config_type'];
				redirect('?type='.$type_val);	
			}
			
			//
			if(in_array($config['attrs']['config_type'],array('sets','node'))){
				$this->assign('field_name',array_prefix('field_',$config['attrs']));
				$this->assign('field_rule',array_prefix('rule_',$config['attrs']));
				$this->assign('config_fields',explode(',',$config['attrs']['config_fields']));
			}
			
			$this->assign('rule',$this->tonavi_type_rule($type));
			$this->assign('type',$type);
			$this->assign('types',$types);
			$this->assign('navi',$navi);
			$this->assign('parentsid',$parentsid);
			$this->assign('navi_actions',$navi_actions);
			
			$this->display('tonavi_config');
		}
		
	}
	
	
	//获取每种config type 类型 字段验证规则
	private function tonavi_type_rule($type){
		
		switch($type){
			case 'sets' :	$rule=''; break;
			case 'node' :	$rule=',config_level:{ required:true ,ptint:true}'; break;
			case 'opts' :	$rule=''; break;
		}
		
		return $rule;
	}
	
	//获取指定navi_id 栏目的所有父级
	private function tonavi_get_parentsid($navi_id){
		
		$sql = "SELECT group_concat(T2.navi_id) as parentsid FROM ( SELECT @r AS _id, (SELECT @r := parent_id FROM __PREFIX__sys_navi WHERE navi_id = _id) AS parent_id,  @l := @l + 1 AS lvl FROM  (SELECT @r := {$navi_id}, @l := 0) vars, __PREFIX__sys_navi h WHERE @r <> 0) T1 JOIN __PREFIX__sys_navi T2 ON T1._id = T2.navi_id ORDER BY T1.lvl ASC";
		$res = current(M()->query($sql));
		if(!isset($res['parentsid']) || empty($res['parentsid'])){
			return false;	
		}
		
		return array_reverse(explode(',',$res['parentsid']));

	}
	
	//获取指定navi_id 栏目的所有子权限名称 返回数组
	private function tonavi_get_actions($navi_id,$identifier){
		
		$navis = M('sys_navi')->field('action')->where("parent_id={$navi_id}")->select();
		if(empty($navis)){
			return array();	
		}
		return str_replace($identifier.'__','',array_column($navis,'action'));
	}
	
	
	//navi to config 
	private function tonavi_config($config){
		
		$attrs = $config['attrs'];
		$attrs['config_type'] = I('post.config_type');
		if(empty($attrs['config_type'])){
			$this->response('error','数据类型必须选择');
		}
		$attrs['config_name'] = I('post.config_name','','trim,input_filter');
		
		if(empty($_POST['config_rule'])){
			if(isset($attrs['config_rule'])){
				unset($attrs['config_rule']);	
			}			
		}else{
			$attrs['config_rule'] = $_POST['config_rule'];	
		}
		
		$attrs['config_name'] = I('post.config_name','','trim,input_filter');
		if($_POST['show_identifier_name']){
			
			if(empty($_POST['identifier_name'])){
				if(isset($attrs['identifier_name'])){
					unset($attrs['identifier_name']);	
				}
			}else{
				$attrs['identifier_name'] = I('post.identifier_name','','trim,input_filter');	
			}			
			
			if(empty($_POST['identifier_rule'])){
				unset($attrs['identifier_rule']);
			}else{
				$attrs['identifier_rule'] = $_POST['identifier_rule'];	
			}
			
		}else{

			unset($attrs['identifier_name'],$attrs['identifier_rule']);
		}

        //sets 类型 是否显示（启用） ID，添加时间
		if($attrs['config_type']=='sets'){
            //记录ID
			if($_POST['show_config_id']){
				$attrs['config_id'] = '1';	
			}elseif(isset($attrs['config_id'])){
                unset($attrs['config_id']);
            }
            //添加时间
            if($_POST['show_config_time']){
                $attrs['config_time'] = '1';
            }elseif(isset($attrs['config_time'])){
                unset($attrs['config_time']);
            }
			
		}
        //是否显示(启用)排序字段
        if($_POST['show_config_sort']){
            $attrs['config_sort'] = '1';
        }elseif(isset($attrs['config_sort'])){
            unset($attrs['config_sort']);
        }
		
		if($attrs['config_type']=='node'){
			$attrs['config_level'] = I('post.config_level',0,'int');
		}
		
		
		if(in_array($attrs['config_type'],array('sets','node'))){
			
			
			
			//先清除原先自定义字段相关设定
			foreach($attrs as $key=>$val){
				if(substr($key,0,6)=='field_'){
					unset($attrs[$key]);
					unset($attrs['rule_'.substr($key,6)]);
				}
			}				
			//在列表显示的字段
			$config_fields = '';
		
			//如果不为空 接收当前自定义字段数据 
			if(!empty($_POST['field_enname'])){
				foreach($_POST['field_enname'] as $key=>$enname){
					if(isset($_POST['field_cnname'][$key]) && !empty($_POST['field_cnname'][$key])){
						$attrs['field_'.$enname] = $_POST['field_cnname'][$key];
					}
					if(isset($_POST['field_rule'][$key]) && !empty($_POST['field_rule'][$key])){
						$attrs['rule_'.$enname] = $_POST['field_rule'][$key];
					}
					if(isset($_POST['field_show'][$key]) && !empty($_POST['field_show'][$key])){
						$config_fields.=$enname.',';	
					}
				}
			}
			
			if(empty($config_fields)){
				if(isset($attrs['config_fields'])){
					unset($attrs['config_fields']);	
				}
			}else{
				$attrs['config_fields'] = rtrim($config_fields,',');	
			}
			
		}
		
		$result = D('Config')->where("config_id={$config['config_id']}")->setField('attrs',json_encode($attrs));
		if($result===false){
			$this->response('error','网络错误，生成栏目失败 请重试');	
		}
		
		/* 生成栏目 */
		
		//确认生成
		if(empty($_POST['navi_id'])){
			$navi_parent_id = end($_POST['navi_parent_id']);
			if($navi_parent_id===''){
				$navi_parent_id = prev($_POST['navi_parent_id']);		
			}
			
			if($navi_parent_id==='' || $navi_parent_id===false ){
				$this->response('error','所属栏目必须选择');		
			}
			
			$is_new_navi = true;
			
		}else{
		//重新生成
			$navi_id = I('post.navi_id',0,'int');
			$is_new_navi = false;
		}
		
		$db = M('sys_navi');
		
		$navi_action 	 = $_POST['navi_action'];
		$navi_action_map = array('index'=>'列表','insert'=>'添加','update'=>'修改','delete'=>'删除','detail'=>'详情','identifier'=>'标识');		
		
		//确认生成
		if($is_new_navi){
			$navi = array();
			$navi['parent_id'] = $navi_parent_id;
			$navi['navi_name'] = I('post.navi_name','','trim,input_filter');
			$navi['navi_name'] = empty($navi['navi_name']) ? $config['name'] : $navi['navi_name'];
			$navi['module'] = 'config';
			$navi['action'] = "{$config['identifier']}__index";
			$navi['insert_time'] = $_SERVER['REQUEST_TIME'];
			
			$navi_id = $db->add($navi);
			if(empty($navi_id)){
				$this->response('error','网络错误，生成栏目失败 请重试');	
			}
			
			foreach($navi_action as $action){
				
				$navi = array();
				$navi['parent_id'] = $navi_id;
				$navi['navi_name'] = $navi_action_map[$action];
				$navi['module'] = 'config';
				$navi['action'] = "{$config['identifier']}__{$action}";
				$navi['insert_time'] = $_SERVER['REQUEST_TIME'];
				$db->add($navi);				
			}
			$this->response('success','生成栏目成功');
		}else{
		//重新生成
			
			//更改栏目名
			$navi_name = I('post.navi_name','','trim,input_filter');
			$navi_name = empty($navi_name) ? $config['name'] : $navi_name;			
			$db->where("navi_id={$navi_id}")->setField('navi_name',$navi_name);
			
			//处理栏目子权限
			foreach($navi_action_map as $action=>$name){
				//当前action 权限 navi 记录的 navi_id
				$action_navi_id = $db->where("parent_id={$navi_id} and module='config' AND action='{$config['identifier']}__{$action}'")->getField('navi_id');
				
				//如果当前action 不在选择的$navi_action中 并且 $action_navi_id不为空，也就是说之前此action权限存在，那么需要删除此action  navi权限
				if(!in_array($action,$navi_action) && !empty($action_navi_id)){
					$db->delete($action_navi_id);
					
				}else if(in_array($action,$navi_action) && empty($action_navi_id)){
				//如果当前action 在选择的$navi_action中 并且 $action_navi_id为空，也就是说之前此action权限不存在，现在新增了此action的权限 那么需要添加此action  navi权限	
					$navi = array();
					$navi['parent_id'] = $navi_id;
					$navi['navi_name'] = $name;
					$navi['module'] = 'config';
					$navi['action'] = "{$config['identifier']}__{$action}";
					$navi['insert_time'] = $_SERVER['REQUEST_TIME'];
					$db->add($navi);
				}				
			}			
			
			$this->response('success','重新生成栏目成功');
		}
		
		
		
	}
	

/** 
*
* Config Extend 
* 三种config 表单类型 
* sets 用于 一级节点 attrs字段相同;
* opts 用于一级节点 单项配置 个记录attrs 可以不一样;
* node 多级节点 多层关系
*
*/
	
	public function _empty($name){
			
		
	  	list($config_name,$action_name) = explode('__',$name,2);
		if(empty($config_name)){
			$this->response('error','配置参数丢失');
		}
		if(empty($action_name)){
			$this->response('error','未知的操作');	
		}
		
		//
		$this->config = D('Config');
		$this->config->identifier = $config_name;
		$this->config->id = $this->config->id();
		$this->config->attrs = $this->config->attrs();
		
		if(!isset($this->config->attrs['config_type']) || empty($this->config->attrs['config_type'])){
			$this->response('error','配置表单类型未设置!');
		}
		
		//action  按sets,opts,node 三种类型分开处理显示
		$action = 'page_'.$this->config->attrs['config_type'].'_'.$action_name;
	
		
		if(!method_exists($this,$action)){
			$this->response('error','无此项操作！');
		}
		
		$this->assign('cnname',$this->config->name());
		$this->assign('idname',$this->config->identifier);		
		//执行当前操作
		$this->$action();			
			
	}
	
	/* sets 类型配置表单 */
	
	//index sets 类型
	private function page_sets_index(){
		$attrs = $this->config->attrs;
		$attrs['config_fields'] = isset($attrs['config_fields']) ? explode(',',trim($attrs['config_fields'],',')) : array();

		$Page = D('Page');
		$pagehtml = $Page->create($this->config->where("parent_id={$this->config->id}")->count(),'sys_grid',20);
		$pagelist = $this->config->field(true)->where("parent_id={$this->config->id}")->order('sort desc,config_id desc')->limit($Page->limit[0],$Page->limit[1])->select();
		if(!empty($pagelist)){
			foreach($pagelist as &$val){
				$val['attrs'] = empty($val['attrs']) ? array() : json_decode($val['attrs'],true);
			}
			unset($val);
		}		
		
		$this->assign('pagehtml',$pagehtml);
		$this->assign('pagelist',$pagelist);
		$this->assign('attrs',$attrs);
		
		$this->display(__FUNCTION__);
	}
	
	private function page_sets_insert(){
		
		//post request
		if(IS_POST && I('post.submit')){
			call_user_func(array($this,__FUNCTION__.'_post'));
			exit;
		}		
		

		//default get request
		$this->assign('rules',$this->get_field_rule($this->config->attrs));
		$this->assign('fields',array_prefix('field_',$this->config->attrs));
		$this->assign(array_prefix('config_',$this->config->attrs));
		$this->assign(array_prefix('identifier_',$this->config->attrs));
		
		
		$this->display(__FUNCTION__);
	}
	
	private function page_sets_insert_post(){

		$data = array();
		$data['parent_id'] = $this->config->id;
		$data['name'] = I('post.config_name','','input_filter');
		if(isset($this->config->attrs['identifier_name']) && isset($_POST['identifier_name'])){
			$data['identifier'] = I('post.identifier_name','','trim,input_filter');
			if($data['identifier']!=''){
				if(is_numeric($data['identifier'])){
					$this->response('error','你输入的【'.$this->config->attrs['identifier_name'].'】值不能为纯数字。');	
				}			
				if($this->config->checkIdentifier($data['identifier'])){
					$this->response('error','你输入的【'.$this->config->attrs['identifier_name'].'】值已经存在，此项不能重复。');
				}
			}
		}
		$attr_fields = array_prefix('field_',$_POST);
		if(!empty($attr_fields)){
			$data['attrs'] = $this->get_attr_fields_val($attr_fields);
		}
        if (!empty($this->config->attrs['config_sort'])) {
            $data['sort'] = I('post.config_sort',0,'int');
        }

		$data['insert_time'] = $_SERVER['REQUEST_TIME'];
		
		$result = $this->config->add($data);
		
		if($result){
			$this->response('success','添加记录成功');	
		}else{
			$this->response('error','添加记录失败,请重试');		
		}
		
		
	}
	
	private function page_sets_update(){
		
		$config_id = I('get.id',0,'int');
		
		if(empty($config_id)){
			$this->response('error','参数丢失');		
		}
		
		//post request
		if(IS_POST && I('post.submit')){
			call_user_func(array($this,__FUNCTION__.'_post'),$config_id);
			exit;
		}
		
		//default get request
		
		$detail = M('sys_config')->find($config_id);
		$detail['attrs'] = json_decode($detail['attrs'],true);
		
		$this->assign('detail',$detail);
		$this->assign('rules',$this->get_field_rule($this->config->attrs));
		$this->assign('fields',array_prefix('field_',$this->config->attrs));
		$this->assign(array_prefix('config_',$this->config->attrs));
		$this->assign(array_prefix('identifier_',$this->config->attrs));		
		
		
		$this->display(__FUNCTION__);	
	}
	
	
	private function page_sets_update_post($config_id){
		
		$data = array();
		$data['config_id'] = $config_id;
		$data['name'] = I('post.config_name','','input_filter');
		
		if(isset($this->config->attrs['identifier_name']) && isset($_POST['identifier_name'])){
			$data['identifier'] = I('post.identifier_name','','trim,input_filter');
			if($data['identifier']!=''){
				if(is_numeric($data['identifier'])){
					$this->response('error','你输入的【'.$this->config->attrs['identifier_name'].'】值不能为纯数字。');	
				}
				if($this->config->where("identifier='{$data['identifier']}' and config_id!={$config_id}")->count()){
					$this->response('error','你输入的【'.$this->config->attrs['identifier_name'].'】值已经存在，此项不能重复。');
				}
			}
		}
		$attr_fields = array_prefix('field_',$_POST);
		if(!empty($attr_fields)){
			$data['attrs'] = $this->get_attr_fields_val($attr_fields);
		}

        if (!empty($this->config->attrs['config_sort'])) {
            $data['sort'] = I('post.config_sort',0,'int');
        }
		
		$result = $this->config->save($data);
		
		if($result===false){
			$this->response('error','修改记录失败,请重试');
		}else{
			$this->response('success','修改记录成功');
		}
		
		
	}
	
	private function page_sets_detail(){
		
		$config_id = I('get.id',0,'int');
		
		if(empty($config_id)){
			$this->response('error','参数丢失');		
		}
		
		//post request
		if(IS_POST && I('post.submit')){
			call_user_func(array($this,__FUNCTION__.'_post'),$config_id);
			exit;
		}
		
		//default get request
		
		$detail = M('sys_config')->find($config_id);
		$detail['attrs'] = json_decode($detail['attrs'],true);
		
		$this->assign('detail',$detail);
		$this->assign('rules',$this->get_field_rule($this->config->attrs));
		$this->assign('fields',array_prefix('field_',$this->config->attrs));
		$this->assign(array_prefix('config_',$this->config->attrs));
		$this->assign(array_prefix('identifier_',$this->config->attrs));		
		
		
		$this->display(__FUNCTION__);	
	}	
	
	
	private function page_sets_delete(){
		
		$id = $_POST['id'];
		
		if(is_array($id)){
			$id = implode(',',$id);	
		}
		
		if(empty($id)){
			$this->response('error','删除失败,参数丢失');	
		}
		
		$result = $this->config->where("config_id in({$id})")->delete();
		
		if($result===false){
			$this->response('error','删除失败,请重试');		
		}else{
			$this->response('success','删除成功');		
		}
	
	}
	
	/* opts 类型配置表单 */
	
	//index opts 类型
	private function page_opts_index(){
	
		$Page = D('Page');
		$pagehtml = $Page->create($this->config->where("parent_id={$this->config->id}")->count(),'sys_grid',20);
		$pagelist = $this->config->field(true)->where("parent_id={$this->config->id}")->order('sort desc,config_id desc')->limit($Page->limit[0],$Page->limit[1])->select();
		$show_attr_value = false;
		if(!empty($pagelist)){
			foreach($pagelist as &$val){
				$val['attrs'] = empty($val['attrs']) ? array() : json_decode($val['attrs'],true);
                if(isset($val['attrs']['value']) && $val['attrs']['value']!==''){
                    $show_attr_value = true;
                }				
			}
			unset($val);
		}		
		
		$this->assign('pagehtml',$pagehtml);
		$this->assign('pagelist',$pagelist);
		$this->assign('show_attr_value',$show_attr_value);
		$this->assign('attrs',$this->config->attrs);
		
		$this->display(__FUNCTION__);
	}
	
	private function page_opts_insert(){
		
		//post request
		if(IS_POST && I('post.submit')){
			call_user_func(array($this,__FUNCTION__.'_post'));
			exit;
		}		
		
		//default get request
		$this->assign(array_prefix('config_',$this->config->attrs));
		$this->assign(array_prefix('identifier_',$this->config->attrs));
		
		
		$this->display(__FUNCTION__);
	}
	
	private function page_opts_insert_post(){
		$data = array();
		$data['parent_id'] = $this->config->id; 
		
		if(isset($this->config->attrs['identifier_name']) && isset($_POST['identifier_name'])){
			$data['identifier'] = I('post.identifier_name','','trim,input_filter');
			if($data['identifier']!=''){
				if(is_numeric($data['identifier'])){
					$this->response('error','你输入的【'.$this->config->attrs['identifier_name'].'】值不能为纯数字。');	
				}
				if($this->config->where("identifier='{$data['identifier']}' and config_id!={$config_id}")->count()){
					$this->response('error','你输入的【'.$this->config->attrs['identifier_name'].'】值已经存在，此项不能重复。');
				}
			}
		}		
		
		$data['name'] = I('post.config_name','','html_escape'); 
		if(empty($data['name'])){
			$this->response('error','配置名称不能为空');
		}
        if ($this->config->attrs['identifier_name']) {
            $data['sort'] = I('post.sort',0,'intval');
        }

		$data['insert_time'] = $_SERVER['REQUEST_TIME'];
		
		
		$data['attrs'] = $this->config->parseAttrs($_POST['attrs_key'],$_POST['attrs_val']);

		$result = $this->config->add($data);	

		if($result){
			$this->response('success','添加记录成功');
		}else{
			$this->response('error','添加记录失败,请重试');	
		}
		
		
	}
	
	private function page_opts_update(){
		$config_id = I('get.id',0,'int');
		
		if(empty($config_id)){
			$this->response('error','参数丢失');		
		}
		
		//post request
		if(IS_POST && I('post.submit')){
			call_user_func(array($this,__FUNCTION__.'_post'),$config_id);
			exit;
		}
		
		//default get request
		
		$detail = M('sys_config')->find($config_id);
		$detail['attrs'] = json_decode($detail['attrs'],true);
		
		$this->assign('detail',$detail);
		$this->assign(array_prefix('config_',$this->config->attrs));
		$this->assign(array_prefix('identifier_',$this->config->attrs));		
		
		
		$this->display(__FUNCTION__);	
	}
	
	
	private function page_opts_update_post($config_id){
		
		$data = array();
		$data['config_id'] = $config_id;
		$data['name'] = I('post.config_name','','input_filter');
		
		if(isset($this->config->attrs['identifier_name']) && isset($_POST['identifier_name'])){
			$data['identifier'] = I('post.identifier_name','','trim,input_filter');
			if($data['identifier']!=''){
				if(is_numeric($data['identifier'])){
					$this->response('error','你输入的【'.$this->config->attrs['identifier_name'].'】值不能为纯数字。');	
				}			
				if($this->config->where("identifier='{$data['identifier']}' and config_id!={$config_id}")->count()){
					$this->response('error','你输入的【'.$this->config->attrs['identifier_name'].'】值已经存在，此项不能重复。');
				}
			}
		}
		$attr_fields = array_prefix('field_',$_POST);
		if(!empty($attr_fields)){
			$data['attrs'] = $this->get_attr_fields_val($attr_fields);
		}
        if ($this->config->attrs['identifier_name']) {
            $data['sort'] = I('post.sort',0,'intval');
        }
		
		$result = $this->config->save($data);
		
		if($result===false){
			$this->response('error','修改记录失败,请重试');
		}else{
			$this->response('success','修改记录成功');
		}
		
		
	}
	
	private function page_opts_detail(){
		
		$config_id = I('get.id',0,'int');
		
		if(empty($config_id)){
			$this->response('error','参数丢失');		
		}
		
		//post request
		if(IS_POST && I('post.submit')){
			call_user_func(array($this,__FUNCTION__.'_post'),$config_id);
			exit;
		}
		
		//default get request
		
		$detail = M('sys_config')->find($config_id);
		$detail['attrs'] = json_decode($detail['attrs'],true);
		
		$this->assign('detail',$detail);
		$this->assign(array_prefix('config_',$this->config->attrs));
		$this->assign(array_prefix('identifier_',$this->config->attrs));		
		
		
		$this->display(__FUNCTION__);	
	}	
	
	
	private function page_opts_delete(){
		
		$id = $_POST['id'];
		
		if(is_array($id)){
			$id = implode(',',$id);	
		}
		
		if(empty($id)){
			$this->response('error','删除失败,参数丢失');	
		}
		
		$result = $this->config->where("config_id in({$id})")->delete();
		
		if($result===false){
			$this->response('error','删除失败,请重试');		
		}else{
			$this->response('success','删除成功');		
		}
	
	}	
	
	/* node 类型配置表单 */
	
	//index node 类型
	private function page_node_index(){

		$attrs = $this->config->attrs;
		
		$attrs['config_fields'] = isset($attrs['config_fields']) ? explode(',',trim($attrs['config_fields'],',')) : array();
		
		$nodes = D('config')->getSubNodes($this->config->id);
		
		$this->assign('nodes',$nodes);
		$this->assign('attrs',$attrs);
		$this->assign('top_config_id',$this->config->id);
		
		$this->display(__FUNCTION__);
	}
	
		
	private function page_node_insert(){
		
		$pid = I('get.pid',0,'int');
		if(empty($pid)){
			$this->response('error','参数丢失');	
		}
		if($pid<$this->config->id){
			$this->response('error','参数过界');		
		}
		
		//POST request
		if(IS_POST && I('post.submit')){
			call_user_func(array($this,__FUNCTION__.'_post'),$pid);
			exit;
		}		
		
		//GET request
		
		$this->node_path = $this->get_node_path($pid);
		
		$this->assign('rules',$this->get_field_rule($this->config->attrs));
		$this->assign('fields',array_prefix('field_',$this->config->attrs));
		$this->assign(array_prefix('config_',$this->config->attrs));
		$this->assign(array_prefix('identifier_',$this->config->attrs));
		
		$this->display(__FUNCTION__);
	}
	
	private function page_node_insert_post($pid){
		
		$data = array();
		$data['parent_id'] = $pid;
		$data['name'] = I('post.config_name','','input_filter');
		
		if(isset($this->config->attrs['identifier_name']) && isset($_POST['identifier_name'])){
			$data['identifier'] = I('post.identifier_name','','trim,input_filter');
			if($data['identifier']!=''){
				if(is_numeric($data['identifier'])){
					$this->response('error','你输入的【'.$this->config->attrs['identifier_name'].'】值不能为纯数字。');	
				}			
				if($this->config->checkIdentifier($data['identifier'])){
					$this->response('error','你输入的【'.$this->config->attrs['identifier_name'].'】值已经存在，此项不能重复。');
				}
			}
		}		

		$attr_fields = array_prefix('field_',$_POST);
		if(!empty($attr_fields)){
			$data['attrs'] = $this->get_attr_fields_val($attr_fields);
		}
		$data['sort'] = I('post.config_sort',0,'int');
		$data['insert_time'] = $_SERVER['REQUEST_TIME'];
		
		$result = $this->config->add($data);
		
		if($result){
			$this->response('success','添加记录成功');	
		}else{
			$this->response('error','添加记录失败,请重试');		
		}
		
		
	}	
	
	
	private function page_node_update(){
		
		$config_id = I('get.id',0,'int');
		
		if(empty($config_id)){
			$this->response('error','参数丢失');		
		}
		
		//post request
		if(IS_POST && I('post.submit')){
			call_user_func(array($this,__FUNCTION__.'_post'),$config_id);
			exit;
		}
		
		//default get request
		$this->node_path = $this->get_node_path($this->config->getParentIdByConfigId($config_id));
		$detail = M('sys_config')->find($config_id);
		$detail['attrs'] = json_decode($detail['attrs'],true);
		
		$this->assign('detail',$detail);
		$this->assign('rules',$this->get_field_rule($this->config->attrs));
		$this->assign('fields',array_prefix('field_',$this->config->attrs));
		$this->assign(array_prefix('config_',$this->config->attrs));
		$this->assign(array_prefix('identifier_',$this->config->attrs));		
		
		
		$this->display(__FUNCTION__);	
	}
	
	
	private function page_node_update_post($config_id){
		
		$data = array();
		$data['config_id'] = $config_id;
		$data['name'] = I('post.config_name','','input_filter');
		
		if(isset($this->config->attrs['identifier_name']) && isset($_POST['identifier_name'])){
			$data['identifier'] = I('post.identifier_name','','trim,input_filter');
			if($data['identifier']!=''){
				if(is_numeric($data['identifier'])){
					$this->response('error','你输入的【'.$this->config->attrs['identifier_name'].'】值不能为纯数字。');	
				}
				if($this->config->where("identifier='{$data['identifier']}' and config_id!={$config_id}")->count()){
					$this->response('error','你输入的【'.$this->config->attrs['identifier_name'].'】值已经存在，此项不能重复。');
				}
			}
		}
		$attr_fields = array_prefix('field_',$_POST);
		if(!empty($attr_fields)){
			$data['attrs'] = $this->get_attr_fields_val($attr_fields);
		}
		$data['sort'] = I('post.config_sort',0,'int');
		$data['insert_time'] = $_SERVER['REQUEST_TIME'];
		
		$result = $this->config->save($data);
		
		if($result===false){
			$this->response('error','修改记录失败,请重试');
		}else{
			$this->response('success','修改记录成功');
		}
		
		
	}
	
	private function page_node_detail(){
		
		$config_id = I('get.id',0,'int');
		
		if(empty($config_id)){
			$this->response('error','参数丢失');		
		}

		$this->node_path = $this->get_node_path($this->config->getParentIdByConfigId($config_id));
		$detail = M('sys_config')->find($config_id);
		$detail['attrs'] = json_decode($detail['attrs'],true);
		
		$this->assign('detail',$detail);
		$this->assign('rules',$this->get_field_rule($this->config->attrs));
		$this->assign('fields',array_prefix('field_',$this->config->attrs));
		$this->assign(array_prefix('config_',$this->config->attrs));
		$this->assign(array_prefix('identifier_',$this->config->attrs));		
		
		
		$this->display(__FUNCTION__);	
	}		
	
	
	private function page_node_delete(){
		
		$config_id = I('post.config_id',0,'intval');
		if(empty($config_id)){
			$this->response('error','参数丢失');	
		}
		$Config = D('Config');
		$Config->configDelete($config_id);
		$result = $Config->where("config_id={$config_id}")->delete()===false ? false : true;
		
		if($result){
			$this->response('success','删除成功');
		}else{
			$this->response('error','网络故障，删除失败');	
		}
	
	}	
	
	
	/* aid function  */
	
	private function get_field_rule($attrs){
		$rule_list = array_prefix('rule_',$attrs);
		$rule_code = '';
		foreach($rule_list as $key=>$rule){
			$field_key = 'field_'.substr($key,5);
			$rule_code.= '"'.$field_key.'":{'.$rule.'},';
		}
		
		return $rule_code;
	}
	
	private function get_attr_fields_val($fields){
		
		$attrs = array();
		foreach($fields as $key=>$val){
			$attrs[substr($key,6)] = $val;	
		}
		return json_encode($attrs);
	}
	
	private function get_node_path($config_id){
		
		$sql = "SELECT T2.config_id, T2.name FROM ( SELECT @r AS _id, (SELECT @r := parent_id FROM __PREFIX__sys_config WHERE config_id = _id) AS parent_id,  @l := @l + 1 AS lvl FROM  (SELECT @r := {$config_id}, @l := 0) vars, __PREFIX__sys_config h WHERE @r <> 0) T1 JOIN __PREFIX__sys_config T2 ON T1._id = T2.config_id ORDER BY T1.lvl DESC";
		$res = $this->config->query($sql);
		$path = '<ul class="config-node-path"><li>'.($this->config->name()).'</li>';
		$top_id = $this->config->id;
		foreach($res as $val){
			if($val['config_id']>$top_id){
				$path.='<li>'.$val['name'].'</li>';
			}
		}
		return $path.'</ul>';
	}
	//厂家列表
	function Factory_management(){
		$this->top_config_id = M()->table('ly_sys_factory')->select();
		$this->list = M()->table('ly_sys_factory')->select();
		$this->display();
	}

	//厂家添加
	function Factory_insert(){
		if($_POST){
			$data['name'] = $_POST['name'];
			$data['py'] = $_POST['py'];
			$data['sort'] = $_POST['sort'];
			$result = M()->table('ly_sys_factory')->add($data);
			if($result){
				$this->response('success','添加成功');
			}else{
				$this->response('error','网络故障，删除失败');	
			}
		}else{
			$this->display();
		}
	}


	//厂家修改
	function Factory_update(){
		if($_POST['name']){
			$id = $_POST['id'];
			$data['name'] = $_POST['name'];
			$data['py'] = $_POST['py'];
			$data['sort'] = $_POST['sort'];
			$result = M()->table('ly_sys_factory')->where("id = {$id}")->save($data);
			if($result){
				$this->response('success','修改成功');
			}else{
				$this->response('error','网络故障，删除失败');	
			}
		}else{
			$id = I('id');
			$this->data = M()->table('ly_sys_factory')->find($id);
			$this->display();
		}
	}


	//厂家删除
	function Factory_del(){
		$id = I('id');
		$result = M()->table('ly_sys_factory')->where("id={$id}")->delete();
		if($result){
			$this->response('success','删除成功');
		}else{
			$this->response('error','网络故障，删除失败');	
		}
	}
}
