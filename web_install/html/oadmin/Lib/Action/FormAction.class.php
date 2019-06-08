<?php

class FormAction extends BaseAction{

	//当前表单
	protected $form;

	
	public function _empty($name){
		
	  	list($form_name,$action_name) = explode('_',$name,2);
		if(empty($form_name)){
			$this->response('error','表单参数丢失');
		}
		
		$this->form = D('Form')->getForm($form_name);
		$this->assign('form',$this->form);
		
		//action 为空 默认为 index-列表页
		$action = empty($action_name) ? 'index':$action_name;
		if(!method_exists($this,$action)){
			$this->response('error','无此项操作！');
		}
		//当前表单属性配置解析
		//$this->parse_form_attrs();
		
		//执行当前操作
		$this->$action();
		
				
		
	}
	
	private function parse_form_attrs(){
		//$attrs = $this->form['attrs'];
		
	}
	
	
	//form index 列表
	protected function index(){
		
		
		//ajax request
		if(IS_AJAX && I('post.ajax')){
			$ajax = 'ajax_'.I('post.ajax');
			if(!method_exists($this,$ajax)){
				$this->response('error','无此项请求！');
			}			
			$this->$ajax();
			exit;
		}		
		
		
		
		/*  */
		$Form = D('Form');
		
		$search_request = I('get.search_request');
		$where = '1';
		//搜索
		$search_fields = $Form->getSearchFields($this->form['nodes']);
		if($search_request){
			foreach($search_fields as $field){
				if(!empty($field['sql'])){
					$where.= ' and '.$field['sql'];	
				}
			}
		}
		
		//列表显示字段
		$gridview_fields = $Form->getGridviewFields($this->form['nodes']);
		//获取记录数量
		$record_amount = form($this->form['sign'])->field("count(*) as amount")->where($where)->getField('amount');
		$Page = D('Page');
		//从当前表单attrs中获取分页参数 
		$page_size = (!empty($this->form['attrs']['page_size']) && is_numeric($this->form['attrs']['page_size'])) ? $this->form['attrs']['page_size'] : 20;
		$this->page_html = $Page->create($record_amount,'sys_grid',$page_size);		
		$gridview_records = form($this->form['sign'])->where($where)->order('record_id desc')->limit($Page->limit[0],$Page->limit[1])->select();
		/* 权限 */
		//修改权限
		$this->power_update = power($this->form['sign'].'_update');
		//查看详情权限
		$this->power_detail = power($this->form['sign'].'_detail');
		//删除权限
		$this->power_delete = power($this->form['sign'].'_delete');
		//是否有以上任意权限
		$this->power_record = ($this->power_update || $this->power_detail || $this->power_delete);
		//search datepicker
		$this->has_datepicker =  $Form->hasField($this->form['nodes'],'datepicker');	
		
		$this->assign('search_fields',$search_fields);
		$this->assign('search_request',$search_request);
		$this->assign('gridview_fields',$gridview_fields);
		$this->assign('gridview_records',$gridview_records);
		$this->assign('FormModel',$Form);
		
		
		$this->display(__FUNCTION__);
	  
	}
	
	//form detail 详情
	protected function detail(){
		
		$record_id = I('get.record_id',0,'intval');
		if(empty($record_id)){
			$this->response('error','参数丢失');	
		}
		
		//多内容字段单独展示判断
		if(isset($_GET['disfield']) && !empty($_GET['disfield']) && is_numeric($_GET['disfield'])){
			$this->detail_disfield($record_id,I('get.disfield',0,'intval'));
			exit;
		}
		
		
		//
		$Form = D('Form');
		
		//获取详情
		$this->details = $Form->getDetails($record_id,$this->form);
		//
		if(empty($this->details)){
			$this->response('error','此记录不存在或是已经删除');	
		}
		
		$this->display(__FUNCTION__);
	  
	}
	
	protected  function detail_disfield($record_id,$field_id){
		
		$this->field_name = config($field_id)->name();
		
		$this->field_val = D('Form')->getFieldValue($record_id,$field_id);
		$this->display(__FUNCTION__);
	}	
	
	
	//form insert 插入
	protected function insert(){
		
		$Form = D('Form');
		
		//ajax request
		if(IS_AJAX && I('post.ajax')){
			$ajax = 'ajax_'.I('post.ajax');
			if(!method_exists($this,$ajax)){
				$this->response('error','无此项请求！');
			}			
			$this->$ajax();
			ex