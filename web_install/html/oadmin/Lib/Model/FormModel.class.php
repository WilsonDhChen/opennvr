<?php

/**
 * Form 自定义表单模型
 * From自定义表单规则说明
 *-------------------------------------------------------------------------------------
 * type 字段类型
 * 支持 text,password,file,radio,checkbox,select,textarea,email,url,number,range,datetime,date,time,week,month,search,color,ueditor,datepicker
 *------------------------------------------------------------------------------------- 
 * text 单行文本框, 
 * 支持的属性有
 * default 默认值
 * width,height,font-size,border,background,font-bold……等css属性
 * style 原生css设定  对当前<input type="text" > 生效
 * placeholder 占位提示字符
 * disabled,readonly,maxlength,size
 *----------------------------------------------------------------------------------------------- 
 * select 下拉列表
 * 支持的属性有 multiple是否多选,size高度, data数据源(来自配置),selectholder首项下拉提示占位 如【请选择】
 *-----------------------------------------------------------------------------------------------
 *
 */

class FormModel extends Model{
	
	protected $form;
	
	//获取表单模型
	public function getForm($form_name){
		
		$config = config($form_name.'@form');
		
		$form = array();
		$form['sign'] = $form_name; 
		$form['id'] = $config->id();
		$form['name'] = $config->name();
		
		if(empty($form['sign']) || empty($form['name']) || empty($form['id'])){
			return false;	
		}
		//获取当前表单模型设置
		$form['attrs'] = $config->attrs();

		$form['nodes'] = $config->nodes(true);
		return $form;
	}
	
	
	//检测表单是否有指定字段类型
	public function hasField($nodes,$type){
		
		static $types = array();
		
		if(empty($types)){

			foreach($nodes as $node){
				$types[] = $node['attrs']['type'];
			}
			$types = array_unique($types);
		}
		
		return in_array($type,$types);

	}	
	
	
	//获取表单字段
	public function getFields($form){
		
		$fields = array();
		$length = count($form['nodes']);
		foreach($form['nodes'] as $node){
			$parse_field_handler = 'handler_field_'.$node['attrs']['type'];
			//不存在的type类型跳过 或 隐藏域也跳过
			if( $node['attrs']['type']=='hidden' || !method_exists($this,$parse_field_handler) ){
				continue;	
			}
            if ($node['attrs']['type'] == 'file') {
                $zindex = empty($node['attrs']['zindex']) ? $length : $node['attrs']['zindex'];
                $fields[] = $this->$parse_field_handler($node,$zindex);
            } else {
                $fields[] = $this->$parse_field_handler($node);
            }

            $length--;
		}
		
		return $fields;
	}

	
	//获取表单验证规则
	public function getRules($nodes){
		
		$rules = '';
		foreach($nodes as $node){
			if(empty($node['attrs']['rule'])){
				continue;	
			}
			$rules.= '"'.$this->parse_name($node['config_id'],$node['attrs']).'":{'.$node['attrs']['rule'].'},';
			
		}
		
		return rtrim($rules,',');
	}
	
	
	//获取记录详情
	public function getDetails($record_id,$form){
		
		$record = $this->table('__SYS_FORM_FIELD__')->field("field_id,record_id,form_id,IFNULL(`val_line`,`val_area`) as `field_val`")->where("record_id='{$record_id}'")->select();
		if(empty($record)){
			return false;	
		}
		$record = array_related('field_id',$record);
		$fields = array();
		foreach($form['nodes'] as $node){
			$fields[] = $this->parseDetailFieldVars($record[$node['config_id']]['field_val'],$node,$record_id);
		}
		
		return $fields;			
		
	}
	
	//获取指定记录id 的记录值, 返回关联数组 array( 'field_id(config_id)'=>field_val ) 
	public function getRecordValues($record_id){
		$result = $this->table('__SYS_FORM_FIELD__')->field("`field_id`,IFNULL(`val_line`,`val_area`) as `field_val`")->where("record_id='{$record_id}'")->select();
		if(empty($result)){
			return false;	
		}
		$record = array();
		foreach($result as $val){
			$record[$val['field_id']] = $val['field_val'];
		}
		
		return $record;
	}
	
	//获取指定记录id、字段id的值
	public function getFieldValue($record_id,$field_id){
		
		$record = $this->table('__SYS_FORM_FIELD__')->field("IFNULL(`val_line`,`val_area`) as `field_val`")->where("record_id='{$record_id}' and field_id='{$field_id}'")->find();
		if(empty($record)){
			return false;	
		}
		return $record['field_val'];			
		
	}	
	
	
	//删除
	public function RecordDelete($record_id,$form){
		
		//检测文件、删除文件
		if($this->hasField($form['nodes'],'file')){
			$this->record_delete_file($form['nodes'],$record_id);
		}
		//删除记录
		M('sys_form_record')->where("record_id='{$record_id}'")->delete();
		M('sys_form_field')->where("record_id='{$record_id}'")->delete();
		return true;
		
	}

	
	//新增记录
	public function PostInert($form){
		
		//关键参数检测
		if( empty(array_filter(array($form['sign'],$form['id'],$form['nodes']))) ){
			return $this->response('error','参数丢失');
		}
		
		//当前form对象
		$this->form = $form;
		
		//验证字段、预处理
		foreach($form['nodes'] as &$node){
			
			//field字段输入值类型
			$node['attrs']['input'] = ( !empty($node['attrs']['input']) && in_array($node['attrs']['input'],array('line','area')) ) ? $node['attrs']['input'] : $this->value_input($node['attrs']);			
			
			//file字段特殊处理
			if($node['attrs']['type']=='file' ){
				//文件域是否设置了必须上传
				if( $node['attrs']['required']=='required'){
					//文件数据信息
					$file = $this->file_value($node['config_id'],$node['attrs']);
					//多文件上传处理
					if($node['attrs']['multiple']=='multiple'){
						//多文件根据每个文件的error值判断，用户具体是否上传了文件
						//使用array_filter过滤掉error=0的，如果过滤后数组数量等于原数组数量 说明没有一个文件被正确上传。
						if(count($file['error'])==count(array_filter($file['error']))){
							return $this->response('error',$node['name'].'必须上传');
							break;							
						}	
					}
					
					//单文件上传处理
					if($file['error']==4){
						return $this->response('error',$node['name'].'必须上传');
						break;						
					}
					
				}				
			}else{
				$filed_value = $this->post_value($node['config_id'],$node['attrs']);
				//检测字段是否必填
				if( $node['attrs']['required']=='required' && $filed_value=='' ){
					return $this->response('error',$node['name'].'不能为空');
					break;
				}
				//字段unique 唯一性检测
				if( !empty($node['attrs']['unique']) && !$this->check_field_unique($filed_value,$node['config_id'],$node['attrs']['input'])){
					return $this->response('error',$node['name'].' "'.$filed_value.'" 已经存在！');
					break;
				}
	
			}
			
		}
		unset($node);
		
		//开启事务
		$this->startTrans();
		//添加记录值
		$record_id = M('sys_form_record')->add( array('form_id'=>$form['id'],'insert_time' => $_SERVER['REQUEST_TIME'] ));
		if(!$record_id){
			return $this->response('error','抱歉、网络故障添加失败 #1');
		}
		
		//插入记录值
		$model = M('sys_form_field');
		
		foreach($form['nodes'] as $node){
			$data = array();
			$data['field_id'] = $node['config_id'];
			$data['form_id'] = $form['id'];
			$data['record_id'] = $record_id;
			$data[$node['attrs']['input']] = $this->parse_vlaue($node);
			
			$row = $model->add($data);

			if(empty($row)){
				$this->rollback();
				return $this->response('error','抱歉、网络故障添加失败 #2['.$node['name'].']');
				break;
				
			}
			
		}		
		
		$this->commit();
		return $this->response('success','添加成功');
			
	}
	
	//
	public function PostUpdate($form){
		
		//关键参数检测
		$record_id = I('get.record_id',0,'int');
		if( empty(array_filter(array($form['sign'],$form['id'],$form['nodes']))) || empty($record_id)){
			return $this->response('error','参数丢失');
		}
		//检测记录是否存在
		if( !M('sys_form_record')->where("record_id='{$record_id}'")->count() ){
			return $this->response('error','当前更新的记录不存在！');
		}
		
		//当前form对象
		$this->form = $form;		
		
		//获取当前记录原值
		$record = $this->getRecordValues($record_id);
		
		//验证字段、预处理
		foreach($form['nodes'] as &$node){
			
			//field字段输入值类型
			$node['attrs']['input'] = ( !empty($node['attrs']['input']) && in_array($node['attrs']['input'],array('line','area')) ) ? $node['attrs']['input'] : $this->value_input($node['attrs']);			
			
			//file字段特殊处理
			if( $node['attrs']['type']=='file' ){
				//文件域是否设置了必须上传（前提当前记录值不为空）
				if( $node['attrs']['required']=='required' && empty($record[$node['config_id']]) ){
					//文件数据信息
					$file = $this->file_value($node['config_id'],$node['attrs']);
					//多文件上传处理
					if($node['attrs']['multiple']=='multiple'){
						//多文件根据每个文件的error值判断，用户具体是否上传了文件
						//使用array_filter过滤掉error=0的，如果过滤后数组数量等于原数组数量 说明没有一个文件被正确上传。
						if(count($file['error'])==count(array_filter($file['error']))){
							return $this->response('error',$node['name'].'必须上传');
							break;							
						}	
					}
					
					//单文件上传处理
					if($file['error']==4){
						return $this->response('error',$node['name'].'必须上传');
						break;						
					}
					
				}				
			}else{
				$filed_value = $this->post_value($node['config_id'],$node['attrs']);
				//检测字段是否必填
				if( $node['attrs']['required']=='required' && $filed_value=='' ){
					return $this->response('error',$node['name'].'不能为空');
					break;
				}
				//字段unique 唯一性检测
				if( !empty($node['attrs']['unique']) && !$this->check_field_unique($filed_value,$node['config_id'],$node['attrs']['input'],$record_id)){
					return $this->response('error',$node['name'].' "'.$filed_value.'" 已经存在！');
					break;
				}
	
			}
			
		}
		unset($node);
		
		//开启事务
		$this->startTrans();
		//更新记录值
		$update_result = M('sys_form_record')->where("record_id='{$record_id}'")->setField('update_time',$_SERVER['REQUEST_TIME']);
		if(!$update_result){
			return $this->response('error','抱歉、网络故障修改失败 #1');
		}
		
		//插入记录值
		$model = M('sys_form_field');
		
		foreach($form['nodes'] as $node){
			$field = array();
			$field['input'] = $node['attrs']['input'];
			$field['new_value'] = $this->parse_vlaue($node);
			$field['old_value'] = $record[$node['config_id']];
			
			//文件域特殊处理
			if($node['attrs']['type']=='file'){
				//多文件
				if($node['attrs']['multiple']=='multiple'){
					//如果是多文件上传新值为空者跳出 不用更新
					if(empty($field['new_value'])){
						continue;	
					}
					//如果是多文件新值不为空，则原值连接上新值
					$field['new_value'] = trim($field['old_value'].','.$field['new_value'],',');
				}else{
				//单文件
					//新值为空 跳出 不更新
					if(empty($field['new_value'])){
						continue;	
					}
					//新值不为空 则需要删除之前的单文件
					$old_file_path = APP_UFS.substr($field['old_value'],strlen(rtrim(UFS_URL,'/')));
					@unlink($old_file_path);
					//如果是图片文件，缩略图 也需要检测并且删除
					if($node['attrs']['thumb']){
						$this->record_delete_file_thumb($old_file_path,$node['attrs']['thumb']);	
					}					
																
				}
			}else{
			//其他非文件域字段
			
				//如果新值和原值相等跳出 不更新
				if($field['new_value']==$field['old_value']){
					continue;	
				}
			}
			
			//检测此字段之前是否存在记录
			$field_exists = $model->where("record_id='{$record_id}' AND field_id='{$node['config_id']}'")->count();
			//不存在 insert 字段值
			if(empty($field_exists)){
				$field_data=array('record_id'=>$record_id,'field_id'=>$node['config_id'],'form_id'=>$form['id']);
				$field_data[$field['input']] = $field['new_value'];
				$row = $model->data($field_data)->add();
			}else{
			//已存在 update 字段值
				$field['old_value'] = addslashes($field['old_value']);
				$row = $model->where("record_id='{$record_id}' AND field_id='{$node['config_id']}' AND IF(ISNULL(val_area),val_line='{$field['old_value']}',val_area='{$field['old_value']}')")->setField($field['input'],$field['new_value']);
			}
			
			//
			if($row===false){
				$this->rollback();
				return $this->response('error','抱歉、网络故障修改失败 #2['.$node['name'].']');
				break;
				
			}
			
		}		
		
		$this->commit();
		return $this->response('success','修改成功');		
			
		
	}
	
	public function RelatedFieldCheck($config_id,$type,$value,$from,$data=array()){
						
		//获取select attrs 配置
		$attrs = config($config_id)->attrs();
		$related_field_check_func = "related_field_check_{$type}";
		if(method_exists($this,$related_field_check_func)){
			return $this->$related_field_check_func($attrs,$value,$from,$data);
		}else{
			return $this->response('unrelated','#1');
		}		
		
	}
	
	//解析 Detail页面字段的显示
	public function parseDetailFieldVars($content,$node,$record_id){
		
		$attrs = $node['attrs'];
		//可以直接显示的 field type
		if(in_array($attrs['type'],array('text','textarea','tel','email','datepicker','range','number','month','datetime','time','date','password'))){
			return 	array('input'=>$content,'title'=>strip_tags($content),'label'=>$node['name']);
		}
		//field type 网址
		if($attrs['type']=='url'){
			return 	array('input'=>'<a href="'.$content.'" target="_blank">'.$content.'</a>','title'=>'点击新窗口中打开URL:'.$content ,'label'=>$node['name']);
		}
		//field type week周
		if($attrs['type']=='week'){
			return 	array('input'=>'<input type="week" value="'.$content.'" readonly style="border:none;">','title'=>'' ,'label'=>$node['name']);
		}		
		
		//field ueditor 百度编辑器
		if($attrs['type']=='ueditor'){
			return 	array('input'=>'<a class="formui-detail-link" href="'.__URL__.'/'.ACTION_NAME.'/disfield/'.$node['config_id'].'?record_id='.$record_id.'" target="_blank">点击查看</a>','title'=>'点击新窗口中查看'.$node['name'],'label'=>$node['name']);	
		}		
		//field type 文件
		if($attrs['type']=='file'){
			$files = explode(',',$content);
			$file_inputs = '<ul class="formui-files-gridview">';
			foreach($files as $file){
				$file_inputs.='<li>';
				if(in_array(pathinfo($file,PATHINFO_EXTENSION),array('jpg','jpeg','png','gif','bmp','webp'))){
					$file_inputs.= '<a href="'.$file.'" class="file-item-view file-img-view" target="_blank" title="点击查看原图"><img src="'.$file.'" height="50" width="60"></a>';
				}else{
					$file_inputs.= '<a href="'.$file.'" class="file-item-view file-doc-view" target="_blank" title="点击下载文件"><strong>'.(pathinfo($file,PATHINFO_EXTENSION)).'</strong>文件</a>';
				}
				$file_inputs.='</li>';
			}
			$file_inputs.= '</ul>';
			return array('input'=>$file_inputs,'title'=>$node['name'].'所有文件','label'=>$node['name']);
		}
		//field type 颜色
		if($attrs['type']=='color'){
			return 	array('input'=>'<span class="formui-record-colorshow" style="color:#'.ltrim($content,'#').'"></span>','title'=>strip_tags($content),'label'=>$node['name']);
		}
		
		//field type : radio,checkbox,select
		if(in_array($attrs['type'],array('radio','checkbox','select'))){
			
			$content = trim($content,',');
			$items = $this->parse_item($node);
			//单值
			if(!strpos($content,',')){
				return array('input'=>$items[$content],'title'=>$items[$content],'label'=>$node['name']);
			}
			/* 多值处理 */
			$vals = explode(',',$content);
			$text = '';
			//多值 - 来源自定义item (来源一级多选，如 checkbox,或select&multiple)
			if(!preg_match('/^\{.*?\}$/i',$attrs['item'])){
				foreach($vals as $val){
					$text.= $items[$val].',';
				}
				$text = rtrim($text,',');
				return array('input'=>$text,'title'=>$text,'label'=>$node['name']);				
			}
			
			//多值 - 来源多级select
			$vars = $this->parse_var_json($attrs['item']);
			$delimiter = ($attrs['type']=='select')?'»':',';
			//来自系统config程序
			if($vars['from']=='config'){
				
				$first_val = array_shift($vals);
				$text.= $items[$first_val].$delimiter;
				
				foreach($vals as $val){
					$text.= config($val)->name().$delimiter;
				}
				$text = rtrim($text,$delimiter);
				
				return array('input'=>$text,'title'=>$text,'label'=>$node['name']);					
				
			}
			
			//
		}
		
		
		//其他...
		return 	array('input'=>$content,'title'=>strip_tags($content),'label'=>$node['name']);
		
		
	}
	
	//
	public function parseUpdateFieldVars($field_val,$node,$record_id){
		$attrs = $node['attrs'];
		$id = empty($attrs['id']) ? "field{$node['config_id']}" : $attrs['id'];
		$name  = $this->parse_name($node['config_id'],$node['attrs']);
		//可以直接显示的 field type
		if(in_array($attrs['type'],array('text','url','color','tel','email','datepicker','range','number','week','month','datetime','time','date','password'))){
			return 	'$("#'.$id.'").val("'.addslashes($field_val).'");';
		}
		
		//textarea  单独处理需要解决多行值(换行) 情况
		if($attrs['type']=='textarea'){
			return 	'$("#'.$id.'").val('.$this->parse_multiline_field_value_to_jsexpr($field_val).');';
			
		}		
		//ueditor
		if($attrs['type']=='ueditor'){
			return $name.'.setContent('.$this->parse_multiline_field_value_to_jsexpr($field_val).');';
		}
		//
		if($attrs['type']=='select'){
			return 'FormSelectEventChange("#'.$id.'",'.$node['config_id'].',"",'.json_encode(explode(',',$field_val)).')';
		}
		//
		if($attrs['type']=='file'){
			$uploaded = empty($field_val) ? 0:count(explode(',',$field_val));
			$jscode = 'var uploaded = '.$uploaded.';';
			$jscode.= '$("#'.$id.'").data("uploaded",uploaded);';
			$jscode.= 'var $inputFile = $("#'.$id.'").find(":file");';
			$jscode.= '$("#'.$id.'").data("required",$inputFile.prop("required")?true:false);';
			if($uploaded>0){
				$jscode.= 'if($inputFile.prop("required")){ $inputFile.prop("required",false);};';
				$jscode.= '$("#'.$id.'").data("rule-required",witaForm.getRule("'.$name.'","uploaded"));';
				$jscode.= 'if($("#'.$id.'").data("rule-required")){ witaForm.setRule("'.$name.'",{uploaded:false}) };';
			}
			$jscode.= '$("#'.$id.'").append(\'<button type="button" onClick="dialog.frame(\\\''.__URL__.'/'.ACTION_NAME.'/disfield/'.$node['config_id'].'?record_id='.$record_id.'\\\',\\\'记录编号'.$record_id.'的'.$node['name'].'已上传文件\\\')" class="fieldui-file-uploaded" title="查看已上传文件">查看已上传文件</button>\');';
			return $jscode;
		}
		//
		if($attrs['type']=='radio'){
			return 	'$(":radio[name=\''.$name.'\'][value=\''.$field_val.'\']").prop("checked",true);';
		}
		//
		if($attrs['type']=='checkbox'){
			$jscode = 'var '.$id.' = ['.$field_val.'];';
			$jscode.= '$.each('.$id.',function(key,val){ $(":checkbox[name=\''.$name.'\'][value=\'"+val+"\']").prop("checked",true); })';
			return $jscode; 
		}
		
				
		return '';
		
		
	}
	
	//表单修改中字段值中如果是多行 需要转换 ，JS不支持 字符串换行语法;
	private function parse_multiline_field_value_to_jsexpr($value){
		$vals = explode("\r\n",addslashes($value));
		$jsexpr = '[';
		foreach($vals as $val){
			$jsexpr.='"'.$val.'",';
		}
		$jsexpr = rtrim($jsexpr,',');
		$jsexpr.= '].join("\r\n")';
		
		return $jsexpr;
	}
	
	//解析 Gridview 列表字段的显示
	public function parseRecordFieldVars($content,$type,$item){
		
		//可以直接显示的 field type
		if(in_array($type,array('text','tel','email','datepicker','range','number','week','month','datetime','time','date','password'))){
			return 	array('html'=>$content,'title'=>strip_tags($content));
		}
		//field type 网址
		if($type=='url'){
			return 	array('html'=>'<a href="'.$content.'" target="_blank">'.$content.'</a>','title'=>'点击新窗口中打开URL:'.$content);	
		}
		//field type 文件
		if($type=='file'){
			$file = current(explode(',',$content));
			if(in_array(pathinfo($file,PATHINFO_EXTENSION),array('jpg','jpeg','png','gif','bmp','webp'))){
				
				return array('html'=>'<a href="'.$file.'" target="_blank"><img src="'.$file.'" height="40"></a>','title'=>'点击查看大图');
				
			}else{
				
				return array('html'=>'<a href="'.$file.'" target="_blank">'.(pathinfo($file,PATHINFO_BASENAME)).'</a>','title'=>'点击下载此文件');
				
			}
		}
		//field type 颜色
		if($type=='color'){
			return 	array('html'=>'<span class="formui-record-colorshow" style="color:#'.ltrim($content,'#').'"></span>','title'=>strip_tags($content));
		}
		
		//field type : radio,checkbox,select
		if(in_array($type,array('radio','checkbox','select'))){
			$content = trim($content,',');
			$items = $this->parse_item(array('attrs'=>array('item'=>$item)));
			//单值
			if(!strpos($content,',')){
				return array('html'=>$items[$content],'title'=>$items[$content]);
			}
			/* 多值处理 */
			$vals = explode(',',$content);
			$text = '';
			//多值 - 来源自定义item (来源一级多选，如 checkbox,或select&multiple)
			if(!preg_match('/^\{.*?\}$/i',$item)){
				foreach($vals as $val){
					$text.= $items[$val].',';
				}
				$text = rtrim($text,',');
				return array('html'=>$text,'title'=>$text);				
			}
			
			//多值 - 来源多级select
			$vars = $this->parse_var_json($item);
			
			$delimiter = ($type=='select')?'»':',';
			
			//来自系统config程序
			if($vars['from']=='config'){
								
				$first_val = array_shift($vals);
				$text.= $items[$first_val].$delimiter;
				
				foreach($vals as $val){
					$text.= config($val)->name().$delimiter;
				}
				$text = rtrim($text,$delimiter);
				
				return array('html'=>$text,'title'=>$text);					
				
			}
			
			//
		}
		
		//其他...
		return 	array('html'=>$content,'title'=>strip_tags($content));
		
		
	}
	
		
	public function getGridviewFields($nodes){
		
		$fields = array();
		
		foreach($nodes as $node){
			if( empty($node['attrs']['list']) || in_array($node['attrs']['type'],array('ueditor','textarea')) ){
				continue;	
			}
			$field = array();
			$field['type'] = $node['attrs']['type'];
			$field['item'] = isset($node['attrs']['item']) ? $node['attrs']['item'] : false;
			$field['name'] = $node['name'];
			$field['key']  = empty($node['attrs']['name']) ? 'field_'.$node['config_id'] : trim($node['attrs']['name']);
			//列表字段内容显示样式设置(以style属性方式内联于td内显示字段值的p标签内)
			$field['style']  = '';
			//其他设定，如 列宽，对齐方式
			$node['attrs']['list'] = trim($node['attrs']['list']);
			if(preg_match('/^\{.*?\}$/i',$node['attrs']['list']) ){
				$vars = $this->parse_var_json($node['attrs']['list']);
				$field['align'] = empty($vars['align'])? '' : (in_array($vars['align'],array('left','center','right','char','justify'))?' align="'.$vars['align'].'" ':'');
				$field['vlign'] = empty($vars['vlign'])? '' : (in_array($vars['vlign'],array('bottom','middle','top','baseline'))?' vlign="'.$vars['vlign'].'" ':'');
				$field['size']  = empty($vars['size']) ? '' : (is_numeric($vars['size'])?' width="'.$vars['size'].'" ':(preg_match('/^\d+(px|em|%|rem|vh|vw)$/i',$vars['size'])?' style="width:'.$vars['size'].';" ':''));
				$field['style'] = $this->parse_gridview_field_style($vars);
			}
			
			$fields[] = $field;

		}
		
		return $fields;
		
	}
	
	//解析字段列表 字段显示样式
	private function parse_gridview_field_style($vars){
		
		$exclude = array('align','vlign','size');
		$style = '';
		foreach($vars as $key=>$val){
			if(!in_array($key,$exclude)){
				$style.= "{$key}:{$val};";		
			}
		}
		return ' style="'.$style.'" ';
	}
	
	public function getSearchFields($nodes){
		
		$search_types = array('text','number','email','tel','color','select','datepicker','radio','checkbox'/*,'range','url','date','time','datetime','month','week'*/);
		$search_fields = array();
		foreach($nodes as $node){
			if(empty($node['attrs']['search']) || !in_array($node['attrs']['type'],$search_types)){
				continue;	
			}
			$field = array();
			$field['name'] = empty($node['attrs']['name']) ? 'field_'.$node['config_id'] : $node['attrs']['name'];
			$field['value'] = $_GET[$field['name']];
			$vars = $this->get_search_field_vars($node,$field['name'],$field['value']);
			$field['html'] = $vars['html'];
			$field['sql']  = $vars['sql'];
			$search_fields[] = $field;
		}
		return $search_fields;
	}
	
	//获取字段存储值类型
	public function getFieldInput($field_attrs){
		return $this->value_input($field_attrs);	
	}
	
	//删除指定record_id和指定field_id的文件
	public function uploadedFileDelete($file,$thumb){
		
		$file_path = APP_UFS.substr($file,strlen(rtrim(UFS_URL,'/')));
		@unlink($file_path);
		//如果是图片文件，缩略图 也需要检测并且删除
		if($thumb){
		  $this->record_delete_file_thumb($file_path,$thumb);	
		}		
	
	}	
	
	/*  private */
	
	//搜索
	private function get_search_field_vars($node,$name,$value){
		
		if(in_array($node['attrs']['type'],array('radio','checkbox','select'))){
			$handler = 'select';
		}else if($node['attrs']['type']=='datepicker'){
			$handler = 'datepicker';
		}else if($node['attrs']['type']=='color'){
			$handler = 'color';
		}else{
			$handler = 'text';				
		}
		
		$get_search_field_html_func = 'get_search_field_html_'.$handler;
		return $this->$get_search_field_html_func($node,$name,$value);
				
	}
	
	private function get_search_field_html_text($node,$name,$value){
		$vars = array();
		$vars['html'] = '<span class="formui-searchui-item"><label for="search_field_'.$node['config_id'].'">'.$node['name'].':</label><input type="text" id="search_field_'.$node['config_id'].'" name="'.$name.'" value="'.$value.'" class="formui-searchui-text"></span>';
		if($value!==''){
			$vars['sql']  = "(`{$name}` like '%{$value}%')";
		}
		return $vars;			
	}
	
	private function get_search_field_html_select($node,$name,$value){
		
		$vars = array();
		$vars['html'] = '<span class="formui-searchui-item"><label for="search_field_'.$node['config_id'].'">'.$node['name'].':</label><select id="search_field_'.$node['config_id'].'" name="'.$name.'" class="formui-searchui-select" onchange="FormSelectEventChange(this,'.$node['config_id'].')"><option value="">全部</option>';
		$items = $this->parse_item($node);
		foreach($items as $key => $val){
			$vars['html'].= '<option value="'.$key.'"'.($value==$key?' selected="selected"':'').'>'.$val.'</option>';	
		}
		$vars['html'].= '</select></span>';
		if($value!==''){
			$vars['sql']  = "(`{$name}` = '{$value}')";
		}
		return $vars;		
	}
	
	private function get_search_field_html_datepicker($node,$name,$value){
		$vars = array();
		$attrs = $node['attrs'];
		$vars['html'] = '<span class="formui-searchui-item"><label>'.$node['name'].':</label>';
		//触发事件和 My97 DatePicker 配置选项
		$this_attrs = ( empty($attrs['event'])?'onfocus':$attrs['event'] ).'="WdatePicker('.(empty($attrs['option']) ? '':'{'.$attrs['option']).'})" ';
		$this_attrs.=  empty($attrs['readonly']) ? ' ' : ' readonly="readonly" ';		
		$vars['html'].= '<input type="text" name="'.$name.'[]" value="'.$value[0].'" class="formui-searchui-datepicker Wdate" autocomplete="off" '.$this_attrs.'>-';
		$vars['html'].= '<input type="text" name="'.$name.'[]" value="'.$value[1].'" class="formui-searchui-datepicker Wdate" autocomplete="off" '.$this_attrs.'>';
		$vars['html'].= '</span>';
		//起止日期都为空 不参与查询
		if(empty($value[0]) && empty($value[1]) ){
			return $vars;	
		}
		
		if(empty($value[1])){
			$vars['sql']  = "(`{$name}` >= '{$value[0]}' )";
		}else if(empty($value[0])){
			$vars['sql']  = "(`{$name}` <= '{$value[1]}' )";
		}else{
			$vars['sql']  = "(`{$name}` between '{$value[0]}' AND '{$value[1]}' )";			
		}		
		
		
		return $vars;
	}	
	
	private function get_search_field_html_datetime($node,$name,$value){
		$vars = array();
		$vars['html'] = '<span class="formui-searchui-item"><label>'.$node['name'].':</label>';
		$vars['html'].= '<input type="datetime-local" name="'.$name.'[]" value="'.$value.'" class="formui-searchui-datetime">-';
		$vars['html'].= '<input type="datetime-local" name="'.$name.'[]" value="'.$value.'" class="formui-searchui-datetime">';
		$vars['html'].= '</span>';
		if($value!==''){
			$vars['sql']  = "(`{$name}` like '%{$value}%')";
		}
		return $vars;			
	}
	
	private function get_search_field_html_color($node,$name,$value){
		$vars = array();
		$vars['html'] = '<span class="formui-searchui-item"><label for="search_field_'.$node['config_id'].'">'.$node['name'].':</label><input type="color" id="search_field_'.$node['config_id'].'" name="'.$name.'" value="'.$value.'" class="formui-searchui-color"></span>';
		if($value!==''){
			$vars['sql']  = "(`{$name}` = '{$value}')";
		}
		return $vars;			
	}		
				
	
	//删除指定record_id的文件
	private function record_delete_file($nodes,$record_id){
		
		foreach($nodes as $node){
			if($node['attrs']['type']!='file'){
				continue;
			}
			//获取字段当前文件字段记录
			$field = $this->table('__SYS_FORM_FIELD__')->field('IFNULL(`val_line`,`val_area`) as filepath')->where("`record_id`='{$record_id}' and `field_id`='{$node['config_id']}'")->find();
			//多文件逗号分隔
			$files = explode(',',$field['filepath']);
			if(empty($files)){
				continue;	
			}
			//遍历删除
			foreach($files as $file){
				$file_path = APP_UFS.substr($file,strlen(rtrim(UFS_URL,'/')));
				@unlink($file_path);
				//如果是图片文件，缩略图 也需要检测并且删除
				if($node['attrs']['thumb']){
				  $this->record_delete_file_thumb($file_path,$node['attrs']['thumb']);	
				}
			}
			
		}
	
	}
	
	
	
	private function record_delete_file_thumb($source_img_path,$thumb){
		$count = substr_count($thumb,',',1) + 1;
		$path = pathinfo($source_img_path);
		for($i=1;$i<=$count;$i++){
			$thumb_img_path = $path['dirname'].'/'.($path['filename'].'_'.$i).'.'.$path['extension'];
			@unlink($thumb_img_path);
		}
		
	}
	
	
	//select 关联多级关联检测
	private function related_field_check_select($attrs,$value,$from,$data){
		$attrs['item'] = trim($attrs['item']);
		//检测是否是特殊数据来源，
		if(empty($from) && !preg_match('/^\{.*?\}$/i',$attrs['item'])){
			return $this->response('unrelated','#2');
		}
		
		//		
		if(empty($from)){
			$item = $this->parse_var_json($attrs['item']);
			$from = $item['from'];
		}
		//数据来源 config
		if($from == 'config'){
			//尝试获取当前值的子集
			$nodes = config($value)->nodes();
			//如果当前值没有直接 返回无关联标识
			if(empty($nodes)){
				return $this->response('none');
			}
			
			//有子集处理
			$js_data = '';
			if(!empty($data) && is_array($data)){
				$js_data = json_encode(array_map('intval',$data));
			}
			$html = '<select onchange="FormSelectEventChange(this,'.$value.',\'config\''.(empty($js_data)?'':','.$js_data).')" id="field_select_'.$value.'" >';
			foreach($nodes as $node){
				$html.='<option value="'.$node['config_id'].'">'.$node['name'].'</option>';	
			}
			
			$html.='</select>';
			
			return $this->response('related','',$html);
		}
	}
	
	//获取field值(除了 type=file)
	private function post_value($config_id,$field_attrs){
		
		$field_name = rtrim($this->parse_name($config_id,$field_attrs),'[]');
	
		//如果是字符串或是数字直接返回
		if( is_string($_POST[$field_name]) || is_numeric($_POST[$field_name]) ){
			return $_POST[$field_name];
		}
		//如果是数组返回以逗号分隔的字符串
		if(is_array($_POST[$field_name])){
			return implode(',',$_POST[$field_name]);	
		}
		
	}
	
	
	//获取file 文件数据
	private function file_value($config_id,$field_attrs){
		
		$field_name = rtrim($this->parse_name($config_id,$field_attrs),'[]');
		return $_FILES[$field_name];	
		
	}
	
	
	//获取field输入值类型
	private function value_input($field_attrs){
		$input_val = 'val_line';
		if( in_array($field_attrs['type'],array('textarea','ueditor')) ){
			$input_val = 'val_area';
		}else if($field_attrs['type']=='file' && $field_attrs['multiple']=='multiple'){
			$input_val = 'val_area';
		}
		
		return $input_val;
	}
	
	//检测字段值唯一性(如指定 record_id 则排除此record_id)
	private function check_field_unique($field_value,$field_id,$value_input,$record_id=0){
		
		return M('sys_form_field')->where("`field_id`='{$field_id}' and `{$value_input}`='{$field_value}'".(empty($record_id)?"":" and `record_id`!='{$record_id}'"))->count()==0;
	}
	
	//解析一维简单格式json (键值不带引号)
	private function parse_var_json($vars){
		
		if( !preg_match('/^\{(.*?)\}$/i',$vars,$matches) ){
			return array();
		}
		
		$lists = explode(',',$matches[1]);
		$array = array();
		foreach($lists as $val){
			$item = explode(':',$val);
			$array[$item[0]] = $item[1];	
		}
		return $array;
		
	}
	//解析字段设定默认值的 动态值
	private function parse_var_value($value){
		
		$value = trim($value);
		//检测 值是否 需要解析
		if(!preg_match('/^\{.*?\}$/i',$value)){
			return $value;	
		}
		
		
		$vars = $this->parse_var_json($value);
		$parse_var_value_from_func = 'parse_var_value_'.$vars['from'];
		if(method_exists($this,$parse_var_value_from_func)){
			return $this->$parse_var_value_from_func($vars);
		}else{
			return NULL;
		}	
  				
	}
	
	//parse_var_value from function
	private function parse_var_value_function($vars){
		
		if(empty($vars['args'])){
			return call_user_func($vars['data']);	
		}
		
		$args = explode('|',$vars['args']);
		foreach($args as &$arg){
			$arg = trim($arg);
			//引号字符串
			if(preg_match('/^([\'"])(.*?)\1$/',$arg,$matches)){
				$arg = $matches[2];
			}else{
			//变量
				$func = create_function('','return '.$arg.' ;');
				$arg = $func();	
			}
		}
		unset($arg);
		
		return call_user_func_array($vars['data'],$args);
	}
	
	//parse_var_value from param
	private function parse_var_value_param($vars){
		$func = create_function('','return '.$vars['data'].' ;');
		return $func();
	}
	
	//parse_var_value from context
	private function parse_var_value_context($vars){
		$context_filed_value = $this->get_post_form_field_value($vars['data']);
		if(empty($vars['opts'])){
			return $context_filed_value;	
		}
		
		$opts = explode('.',$vars['opts']);
		$opt_handler = 'parse_var_value_context_opt_'.$opts[0];
		if(method_exists($this,$opt_handler)){
			return $this->$opt_handler($opts,$context_filed_value);
		}else{
			return NULL;
		}				
	}
	
	//parse_var_value_context_opt for config
	private function parse_var_value_context_opt_config($opts,$value){
		$config = config($value)->details();
		$length = count($opts);
		if($length==2){
			return $config[$opts[1]];	
		}
		
		if($length==3 && $opts[1]=='attr'){
			return $config['attrs'][$opts[2]];
		}
		
		return NULL;
	}
	
	
	
	//根据当前表单中某字段自定义的name值 获取当前表单提交时该字段的值
	private function get_post_form_field_value($name){
		$field_node = array();
		foreach($this->form['nodes'] as $node){
			if($node['attrs']['name']==$name){
				$field_node = $node;
				break;		
			}
		}
		
		if(empty($field_node)){
			return false;	
		}
		
		$filed_value = $this->post_value($field_node['config_id'],$field_node['attrs']);
		
		return $filed_value;
		
	}
	
	//解析样式
	private function parse_style($attrs){
		
		$css_attrs = array('width','height','max-height','margin','padding','border','background','color','opacity','font','font-size','font-style','font-family','font-weight','letter-spacing','line-height','text-align','text-indent','outline','resize');
		$style = '';
		foreach($attrs as $key => $val){
			if( in_array($key,$css_attrs) ){
				$style .= $key.':'.trim($val,';').';';
			}
		}
		
		if(empty($style)){
			return '';	
		}
		
		if(isset($attrs['style'])){
			$style.= $attrs['style'];	
		}
		
		return ' style="'.$style.'" ';
		
	}
	
	private function parse_attr($attrs,$type=NULL){
		//公共属性
		$public_attr_maps = array('disabled','readonly','tabindex','autofocus','required');
		//私有属性
		$private_attr_maps  = array(
							'text'  		=> array('max_length','size','placeholder','pattern','autocomplete'),
							'textarea'  	=> array('cols','rows','maxlength','wrap','placeholder'),
							'password'		=> array('max_length','size','placeholder','pattern'),
							'file'			=> array('multiple','accept'),
							'radio'			=> array(),
							'checkbox'		=> array(),
							'select'		=> array('multiple','size'),
							'date'		  	=> array('min','max','step'),
							'time'		  	=> array('min','max','step'),
							'datetime'		=> array('min','max','step'),
							'number'		=> array('min','max','step','value'),
							'range'			=> array('min','max','step','value'),
							'hidden'		=> array('value'),
				);
		$maps =  (!empty($type) && is_string($type) && isset($private_attr_maps[$type])) ? array_merge($public_attr_maps,$private_attr_maps[$type]) : $public_attr_maps;
		$attr = '';
		foreach($attrs as $key => $val){
			if( in_array($key,$maps) ){
				$attr .= $key.'="'.trim($val).'" ';
			}
		}
		return $attr;
	}
	
	//解析 radio,checkbox,select option 选项
	private function parse_item($node){
		
		$node['attrs']['item'] = trim($node['attrs']['item']);
		
		if(empty($node['attrs']['item'])){
			return '';	
		}
		
		/* 检测item数据来源 */
		
		//特殊item数据来源
		if(preg_match('/^\{.*?\}$/i',$node['attrs']['item'])){
			$vars = $this->parse_var_json($node['attrs']['item']);
			$parse_item_func = 'parse_item_'.$vars['from'];
			if(method_exists($this,$parse_item_func)){
				return $this->$parse_item_func($vars);
			}else{
				return array();	
			}
		}
		
		//普通字符串分隔原始数据
		$items = explode(',',','.trim($node['attrs']['item'],','));
		unset($items[0]);
		
		return $items;

	}
	
	//解析item 来自 config 的数据来源
	private function parse_item_config($vars){
		if(empty($vars['data'])){
			return array();	
		}
		$data = config($vars['data'])->nodes();
		if(empty($data) || !is_array($data)){
			return array();	
		}
		$items = array();
		foreach($data as $val){
			$items[$val['config_id']] = $val['name'];
		}
		unset($data);
		
		return $items;
	}
	
	//根据字段config_id、类型和字段属性 解析字段 name 名称
	private function parse_name($config_id,$field_attrs){
		
		$name = 'field_'.$config_id;
		
		if( $field_attrs['type']=='file' && $field_attrs['multiple']=='multiple' ){
			$name.='[]';
		}else if( $field_attrs['type']=='checkbox' && strpos($field_attrs['item'],',') ){
			$name.='[]';
		}else if( $field_attrs['type']=='select' && $field_attrs['multiple']=='multiple' ){
			$name.='[]';
		}
		
		return $name;		
	}
	
	//文件上传
	private function parse_upload($files,$thumb){
		//单文件
		if(!is_array($files['error'])){
			return $this->upload_file($files,$thumb);
		}
		//多文件上传
		$file_len = count($files['tmp_name']);
		$value = '';
		for($i=0;$i<$file_len;$i++){
			$file = array();
			$file['name'] 		= $files['name'][$i];
			$file['type'] 		= $files['type'][$i];
			$file['tmp_name'] 	= $files['tmp_name'][$i];
			$file['error'] 		= $files['error'][$i];
			$file['size'] 		= $files['size'][$i];
			
			$value.=$this->upload_file($file,$thumb).',';
		}
		return rtrim($value,',');
		
	}
	//保存上传的文件
	private function upload_file($file,$thumb){
		
		//文件上传出错
		if($file['error']>0){
			return '';	
		}
		//保存路径
		$save_path = APP_UFS.'/form/'.date('Y').'/'.date('m');
		if(!is_dir($save_path)){
			mkdir($save_path,0755,true);	
		}
		//生成随机新文件名
		$file_name = md5(uniqid(mt_rand(1000000,999999999),true)) .'.'. pathinfo($file['name'],PATHINFO_EXTENSION);
		//文件保存全路径
		$file_path = $save_path.'/'.$file_name;
		//
		if(move_uploaded_file($file['tmp_name'],$file_path)){
			//图片文件缩略图生成检测
			if(preg_match('/^image\/\w+$/i',$file['type']) && !empty($thumb)){
				$this->thumb_img($file_path,$thumb);
			}
			//返回需要保存的文件URL 路径
			return rtrim(UFS_URL,'/').substr($file_path,strlen(APP_UFS));
		}
		
		return '';
		
	}
	//图片文件生成缩略图
	private function thumb_img($file,$thumb){
		
		//缩略图尺寸设置是否是 动态值
		if( preg_match('/^\{.*?\}$/i',$thumb) ){
			$thumb = $this->parse_var_value($thumb);
		}
		
		//数据格式 宽度*高度 可以多个逗号分隔, 100*200,50,20*30
		$thumbs = explode(',',$thumb);
		if(empty($thumbs) || !is_array($thumbs)){
			return false;	
		}
		$img = new Images();
		foreach($thumbs as $key => $size){
			if(strpos($size,'*')){
				list($width,$height) = explode('*',$size);
			}else{
				$width  = $height = intval($size);	
			}
			$path = pathinfo($file);
			
			$thumb_file = $path['dirname'].'/'.$path['filename'].'_'.($key+1).'.'.$path['extension'];
			//生成缩略图
			$img->loadFile($file)->thumbnail($width,$height)->save($thumb_file);
		}
		
	}
	
	//获取字段最终存入数据库的值
	private function parse_vlaue($node){
	
		switch($node['attrs']['type']){
			//文件域
			case 'file' 	:	$value = $this->parse_upload($this->file_value($node['config_id'],$node['attrs']),$node['attrs']['thumb']);break;
			//隐藏域(服务器端处理)
			case 'hidden' 	:	$value = $this->parse_var_value($node['attrs']['value']);break;
			//其他字段值
			default     	:   $value = $this->post_value($node['config_id'],$node['attrs']);
		}		
		
		return $value;
	}	
	
	/* field handler */
	
	private function handler_field_text($node){
		$field = array();
		$attrs = $node['attrs'];
		$id = empty($attrs['id']) ? "field{$node['config_id']}" : $attrs['id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		$field['label'] = '<label for="'.$id.'">'.$node['name'].'</label>';
		$field['input'] = '<input type="text" class="formui-'.$type.'" title="'.$node['name'].'" name="'.$this->parse_name($node['config_id'],$node['attrs']).'" id="'.$id.'" '.$this->parse_attr($attrs,$type).$this->parse_style($attrs).'>';
		
		return $field;
	}
	
	private function handler_field_password($node){
		$field = array();
		$attrs = $node['attrs'];
		$id = empty($attrs['id']) ? "field{$node['config_id']}" : $attrs['id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		$field['label'] = '<label for="'.$id.'">'.$node['name'].'</label>';
		$field['input'] = '<input type="password" class="formui-'.$type.'" title="'.$node['name'].'" name="'.$this->parse_name($node['config_id'],$node['attrs']).'" id="'.$id.'" '.$this->parse_attr($attrs,$type).$this->parse_style($attrs).'>';
		
		return $field;
	}
	
	private function handler_field_textarea($node){
		$field = array();
		$attrs = $node['attrs'];
		$id = empty($attrs['id']) ? "field{$node['config_id']}" : $attrs['id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		$field['label'] = '<label for="'.$id.'">'.$node['name'].'</label>';
		$field['input'] = '<textarea class="formui-'.$type.'" title="'.$node['name'].'" name="'.$this->parse_name($node['config_id'],$node['attrs']).'" id="'.$id.'" '.$this->parse_attr($attrs,$type).$this->parse_style($attrs).'></textarea>';
		
		return $field;
	}		
	
	private function handler_field_radio($node){
		
		$field = array();
		$attrs = $node['attrs'];
		$id = "field{$node['config_id']}";
		$name = 'field_'.$node['config_id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		
		$field['label'] = '<label>'.$node['name'].'</label>';
		//解析 radio item 
		$items = $this->parse_item($node);
		
		$field['input'] = '';
		if(empty($items)){
			return 	$field;
		}
		
		foreach($items as $key => $val){
			$field['input'].='<span class="formui-'.$type.'" '.$this->parse_style($node['attrs']).'><input type="radio" id="'.$id.'_'.$key.'" '.($node['attrs']['checked']==$key?'checked':'').' title="'.$node['name'].'" value="'.$key.'" name="'.$this->parse_name($node['config_id'],$node['attrs']).'" '.$this->parse_attr($attrs,$type).' ><label for="'.$id.'_'.$key.'">'.$val.'</label></span>';
		}
		return $field;
	}
	
	private function handler_field_checkbox($node){
		
		$field = array();
		$attrs = $node['attrs'];
		$id = "field{$node['config_id']}";
		$name = 'field_'.$node['config_id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		
		$field['label'] = '<label>'.$node['name'].'</label>';
		//解析 radio item 
		$items = $this->parse_item($node);
		
		$field['input'] = '';
		if(empty($items)){
			return 	$field;
		}
		
		foreach($items as $key => $val){
			$field['input'].='<span class="formui-'.$type.'" '.$this->parse_style($node['attrs']).'><input type="checkbox" id="'.$id.'_'.$key.'" '.($node['attrs']['checked']==$key?'checked':'').' title="'.$node['name'].'" value="'.$key.'" name="'.$this->parse_name($node['config_id'],$node['attrs']).'" '.$this->parse_attr($attrs,$type).' ><label for="'.$id.'_'.$key.'">'.$val.'</label></span>';
		}
		return $field;
	}		
	
	private function handler_field_file($node,$zindex){
		$field = array();
		$attrs = $node['attrs'];
		$id = "field{$node['config_id']}";
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		$field['label'] = '<label for="'.$id.'">'.$node['name'].'</label>';
		$field['input'] = '<div class="formui-'.$type.'" id="'.$id.'" style="z-index:'.$zindex.'"><dl><dt><span class="fieldui-file-numtips">选择文件</span><span class="fieldui-file-selecttips">'.($attrs['multiple']=='multiple'?'(可选多个)':'(仅限一个)').'</span></dt></dl><div class="fieldui-file-trigger"><input class="fieldui-file-input notSelected" title="'.$node['name'].'" type="file" name="'.$this->parse_name($node['config_id'],$node['attrs']).'"  '.$this->parse_attr($attrs,$type).' /></div></div>';
		
		return $field;
	}	
	
	private function handler_field_select($node){
		
		$field = array();
		$attrs = $node['attrs'];
		$id = "field{$node['config_id']}";
		$name = 'field_'.$node['config_id'];
		
		$field['label'] = '<label>'.$node['name'].'</label>';
		//解析 radio item 
		$items = $this->parse_item($node);
		
		$field['input'] = '';
		if(empty($items)){
			return 	$field;
		}
		$id   = 'field' . $node['config_id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		$field['input'] .= '<select class="formui-'.$type.'" name="'.$this->parse_name($node['config_id'],$node['attrs']).'" id="'.$id.'" '.$this->parse_style($node['attrs']).' title="'.$node['name'].'" '.$this->parse_attr($attrs,$type).' onchange="FormSelectEventChange(this,'.$node['config_id'].')">';
		
		//selectholder 占位符，如：请选择
		if($attrs['selectholder']){
			$field['input'].='<option value="#selectholder#">'.$attrs['selectholder'].'</option>';
		}
		
		//option 
		foreach($items as $key => $val){
			$field['input'].='<option value="'.$key.'">'.$val.'</option>';
		}
		$field['input'].='</select>';
		return $field;
	}
	
	private function handler_field_date($node){
		$field = array();
		$attrs = $node['attrs'];
		$id = empty($attrs['id']) ? "field{$node['config_id']}" : $attrs['id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		$field['label'] = '<label for="'.$id.'">'.$node['name'].'</label>';
		$field['input'] = '<input type="date" class="formui-text formui-'.$type.'" title="'.$node['name'].'" name="'.$this->parse_name($node['config_id'],$node['attrs']).'" id="'.$id.'" '.$this->parse_attr($attrs,$type).$this->parse_style($attrs).'>';
		
		return $field;
	}
	
	private function handler_field_time($node){
		$field = array();
		$attrs = $node['attrs'];
		$id = empty($attrs['id']) ? "field{$node['config_id']}" : $attrs['id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		$field['label'] = '<label for="'.$id.'">'.$node['name'].'</label>';
		$field['input'] = '<input type="time" class="formui-text formui-'.$type.'" title="'.$node['name'].'" name="'.$this->parse_name($node['config_id'],$node['attrs']).'" id="'.$id.'" '.$this->parse_attr($attrs,$type).$this->parse_style($attrs).'>';
		
		return $field;
	}
	
	private function handler_field_datetime($node){
		$field = array();
		$attrs = $node['attrs'];
		$id = empty($attrs['id']) ? "field{$node['config_id']}" : $attrs['id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		$field['label'] = '<label for="'.$id.'">'.$node['name'].'</label>';
		$field['input'] = '<input type="datetime-local" class="formui-text formui-'.$type.'" title="'.$node['name'].'" name="'.$this->parse_name($node['config_id'],$node['attrs']).'" id="'.$id.'" '.$this->parse_attr($attrs,$type).$this->parse_style($attrs).'>';
		
		return $field;
	}
	
	private function handler_field_month($node){
		$field = array();
		$attrs = $node['attrs'];
		$id = empty($attrs['id']) ? "field{$node['config_id']}" : $attrs['id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		$field['label'] = '<label for="'.$id.'">'.$node['name'].'</label>';
		$field['input'] = '<input type="month" class="formui-text formui-'.$type.'" title="'.$node['name'].'" name="'.$this->parse_name($node['config_id'],$node['attrs']).'" id="'.$id.'" '.$this->parse_attr($attrs,$type).$this->parse_style($attrs).'>';
		
		return $field;
	}
	
	private function handler_field_week($node){
		$field = array();
		$attrs = $node['attrs'];
		$id = empty($attrs['id']) ? "field{$node['config_id']}" : $attrs['id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		$field['label'] = '<label for="'.$id.'">'.$node['name'].'</label>';
		$field['input'] = '<input type="week" class="formui-text formui-'.$type.'" title="'.$node['name'].'" name="'.$this->parse_name($node['config_id'],$node['attrs']).'" id="'.$id.'" '.$this->parse_attr($attrs,$type).$this->parse_style($attrs).'>';
		
		return $field;
	}		
	
	private function handler_field_number($node){
		$field = array();
		$attrs = $node['attrs'];
		$id = empty($attrs['id']) ? "field{$node['config_id']}" : $attrs['id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		$field['label'] = '<label for="'.$id.'">'.$node['name'].'</label>';
		$field['input'] = '<input type="number" class="formui-text formui-'.$type.'" title="'.$node['name'].'" name="'.$this->parse_name($node['config_id'],$node['attrs']).'" id="'.$id.'" '.$this->parse_attr($attrs,$type).$this->parse_style($attrs).'>';
		
		return $field;
	}
	
	private function handler_field_range($node){
		$field = array();
		$attrs = $node['attrs'];
		$id = empty($attrs['id']) ? "field{$node['config_id']}" : $attrs['id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		$field['label'] = '<label for="'.$id.'">'.$node['name'].'</label>';
		$field['input'] = '<input type="range" class="formui-'.$type.'" title="'.$node['name'].'" name="'.$this->parse_name($node['config_id'],$node['attrs']).'" id="'.$id.'" '.$this->parse_attr($attrs,$type).$this->parse_style($attrs).'>';
		
		return $field;
	}		
	
	private function handler_field_color($node){
		$field = array();
		$attrs = $node['attrs'];
		$id = empty($attrs['id']) ? "field{$node['config_id']}" : $attrs['id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		$field['label'] = '<label for="'.$id.'">'.$node['name'].'</label>';
		$field['input'] = '<input type="color" class="formui-'.$type.'" title="'.$node['name'].'" name="'.$this->parse_name($node['config_id'],$node['attrs']).'" id="'.$id.'" '.$this->parse_attr($attrs,$type).$this->parse_style($attrs).'>';
		
		return $field;
	}
	
	private function handler_field_email($node){
		$field = array();
		$attrs = $node['attrs'];
		$id = empty($attrs['id']) ? "field{$node['config_id']}" : $attrs['id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		$field['label'] = '<label for="'.$id.'">'.$node['name'].'</label>';
		$field['input'] = '<input type="email" class="formui-text formui-'.$type.'" title="'.$node['name'].'" name="'.$this->parse_name($node['config_id'],$node['attrs']).'" id="'.$id.'" '.$this->parse_attr($attrs,$type).$this->parse_style($attrs).'>';
		
		return $field;
	}	
	
	private function handler_field_tel($node){
		$field = array();
		$attrs = $node['attrs'];
		$id = empty($attrs['id']) ? "field{$node['config_id']}" : $attrs['id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		$field['label'] = '<label for="'.$id.'">'.$node['name'].'</label>';
		$field['input'] = '<input type="tel" class="formui-text formui-'.$type.'" title="'.$node['name'].'" name="'.$this->parse_name($node['config_id'],$node['attrs']).'" id="'.$id.'" '.$this->parse_attr($attrs,$type).$this->parse_style($attrs).'>';
		
		return $field;
	}
	
	private function handler_field_url($node){
		$field = array();
		$attrs = $node['attrs'];
		$id = empty($attrs['id']) ? "field{$node['config_id']}" : $attrs['id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		$field['label'] = '<label for="'.$id.'">'.$node['name'].'</label>';
		$field['input'] = '<input type="url" class="formui-text formui-'.$type.'" title="'.$node['name'].'" name="'.$this->parse_name($node['config_id'],$node['attrs']).'" id="'.$id.'" '.$this->parse_attr($attrs,$type).$this->parse_style($attrs).'>';
		
		return $field;
	}
	
	private function handler_field_datepicker($node){
		$field = array();
		$attrs = $node['attrs'];
		$id = empty($attrs['id']) ? "field{$node['config_id']}" : $attrs['id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		$field['label'] = '<label for="'.$id.'">'.$node['name'].'</label>';
		//触发事件和 My97 DatePicker 配置选项
		$this_attrs = ( empty($attrs['event'])?'onfocus':$attrs['event'] ).'="WdatePicker('.(empty($attrs['option']) ? '':'{'.$attrs['option']).'})" ';
		$this_attrs.=  empty($attrs['readonly']) ? ' ' : ' readonly="readonly" ';
		//默认值
		$this_attrs.=' value="'.$this->parse_var_value($attrs['value']).'" ';
		
		//
		$field['input'] = '<input type="text" class="formui-text formui-'.$type.' Wdate" title="'.$node['name'].'"  autocomplete="off" name="'.$this->parse_name($node['config_id'],$node['attrs']).'" id="'.$id.'" '.$this_attrs.$this->parse_style($attrs).'>';
		
		return $field;
	}
	
	private function handler_field_ueditor($node){
		$field = array();
		$attrs = $node['attrs'];
		$id = empty($attrs['id']) ? "field{$node['config_id']}" : $attrs['id'];
		$type = substr(__FUNCTION__,strripos(__FUNCTION__,'_')+1);
		$field['label'] = '<label for="'.$id.'">'.$node['name'].'</label>';
		
		//attrs配置传给编辑器
		$config = $attrs;
		//排除type项
		unset($config['type']);
		//配置项目中的字符串布尔值转换 转换成 真实的布尔值 true or false
		$config = array_map(create_function('$val',' $val = strtoupper($val) ; if($val==="FALSE"){ return false; }elseif($val==="TRUE"){  return true; }else{ return $val;};'),$config);
		//是否保持toolbar的位置不动,ueditor原先默认true，现在此处改为默认false
		if(!isset($config['autoFloatEnabled'])){
			$config['autoFloatEnabled'] = false;	
		}
		$config['content'] = '';
		$config['id'] = $id;
		$config['name']  = $config['var']  = $this->parse_name($node['config_id'],$node['attrs']);
		
		//
		$field['input'] = '<div>'.W('UEditor',$config,true).'</div>';
		
		return $field;
	}					
	

	
}