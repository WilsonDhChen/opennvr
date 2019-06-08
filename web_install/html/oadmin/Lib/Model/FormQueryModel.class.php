<?php

/**
 * FormQuery 用户Form自定义表单查询专属模型
 * 
 *-------------------------------------------------------------------------------------
 *
 */

class FormQueryModel{
	
	private $forms  = array();
	
	private $tables = array();
	
	private $sql;
	
	private $field = '*';
	
	private $where = '';
	
	private $limit = '';
	
	private $order = '';		
	
	
	public function setForm($form){
		$this->forms = is_array($form) ? $form : explode(',',$form);
		$this->parse_table();
		return $this;
	}
	
	public function getForm(){
		return 	$this->forms ;
	}
	
	//执行一个form SQL
	public function query($sql){
		$this->sql = $this->parse_sql($sql);
		return M()->query($this->sql);
	}
	
	public function select(){
		return $this->query($this->get_sql().$this->limit);
		
	}
	
	public function find($record_id=NULL){
		if(!is_null($record_id) && is_numeric($record_id)){
			$this->where("record_id={$record_id}");
		}
		return current($this->query($this->get_sql().'limit 1'));
		
	}
	
	public function getField($field){
		
		$record = $this->find();
		return (isset($record[$field]) ? $record[$field] : NULL);
		
	}		
	
	public function count(){
		$this->field('count(*) as rows_count');
		return $this->getField('rows_count');
	}
	
	//
	public function field($expr){
		$this->field = empty($expr) ? '*' : $expr;
		return $this;		
	}
	
	public function where($expr){
		$this->where = empty($expr) ? '' :'where '.$expr;
		return $this;
	}
	
	public function limit($offset,$length=NULL){
		$this->limit ='limit '.(is_null($length) ? $offset : $offset.','.$length);
		return $this;
	}
	
	public function order($expr){
		$this->order = empty($expr) ? '' : 'order by '.$expr; 
		return $this;
	}	
	
	
	//返回上次最终执行的sql
	public function getLastSql(){
		return 	$this->sql;
	}
	
	public function _sql(){
		return 	$this->getLastSql();
	}
			
	
	
	
	/* private */

	
	private function parse_sql($sql){
		
		$tables = array();	
		foreach($this->forms as $form){
			$tables[] = empty($this->tables[$form]) ? $form : $this->tables[$form];
		}
		
		return $this->str_replace_limit($this->forms,$tables,$sql,1);
		
	}
	
	
	private function get_sql(){
		
		$sql = 'select '.$this->field.' from '.$this->forms[0].' '.$this->where.' '.$this->order.' ';
		$this->reset();
		return $sql;
		 
	}
	
	//重置SQL options
	private function reset(){
		
		$this->field = '*';	
		$this->where = '';	
		$this->limit = '';	
		$this->order = '';	
	}
	
		
	
	
	private function parse_table(){
		
		foreach($this->forms as $form){
			//已经解析过的form
			if(!empty($this->tables[$form])){
				continue;
			}
			
			$this->tables[$form] = $this->get_table_source($form);
		}
	}
	
	private function get_table_source($form_name){
		
		$form = $this->form = D('Form')->getForm($form_name);
		//如果为空 返回原值
		if(empty($form)){
			return $form_name;	
		}
		
		//表前缀
		$prefix = C('DB_PREFIX');
		
		$sql = "( SELECT `form_field`.`record_id`,`form_record`.`insert_time`,`form_record`.`update_time`,";
		
		foreach($form['nodes'] as $node){
			$name = empty($node['attrs']['name']) ? 'field_'.$node['config_id'] : $node['attrs']['name'];
			//
			if( in_array($node['attrs']['type'],array('radio','number','range','datetime','date','time','month','datepicker')) ){
				//datepicker cast type 判断
				if($node['attrs']['type']=='datepicker'){
					if(preg_match('/dateFmt\:([\'"]).*?[Hms]+.*?\1/',$node['attrs']['option'])){
						$cast_type = 'DATETIME';
					}else{
						$cast_type = 'DATE';	
					}
				}else{
					switch($node['attrs']['type']){
						case 'radio':
							$cast_type = 'UNSIGNED';break;
						case 'number':
						case 'range':
							$cast_type = 'SIGNED';break;						
						case 'datetime':
							$cast_type = 'DATETIME';break;
						case 'date':
						case 'month':
							$cast_type = 'DATE';break;
						case 'time':
							$cast_type = 'TIME';break;																		
	
					}
				}
				$sql.= "(SELECT CAST(`val_line` as {$cast_type}) from `{$prefix}sys_form_field` where `field_id`='{$node['config_id']}' and record_id=`form_field`.`record_id`) as `{$name}`,";
			}else{
				$sql.= "(SELECT IFNULL(`val_line`,`val_area`) from `{$prefix}sys_form_field` where `field_id`='{$node['config_id']}' and record_id=`form_field`.`record_id`) as `{$name}`,";
			}
		}
		$sql = rtrim($sql,',');
		$sql.= " FROM `{$prefix}sys_form_field` as `form_field` LEFT JOIN `{$prefix}sys_form_record` as `form_record` on `form_record`.`record_id`=`form_field`.`record_id` where `form_field`.`form_id`={$form['id']} GROUP BY `form_field`.`record_id` ) as `{$form_name}`";
		return $sql;
		
	}
	
	//可指定替换次数的字符串替换
	private function str_replace_limit($search, $replace, $subject, $limit=-1) {
	    // constructing mask(s)...
	    if (is_array($search)) {
	        foreach ($search as $k=>$v) {
	            $search[$k] = '`' . preg_quote($search[$k],'`') . '`';
	        }
	    }else {
	        $search = '`' . preg_quote($search,'`') . '`';
	    }
	    // replacement
	    return preg_replace($search, $replace, $subject, $limit);
	}	
	
	
	
	
	

	
}