<?php

class ConfigModel extends Model{
	
	protected $tableName = 'sys_config';
	
	public $identifier;
	
	public $cache = array();

	public $order = null;
	
	
	//获取配置属性中的value 属性的值
	public function value(){
		$details = $this->details();
		return $details['attrs']['value'];
	}
	
	//获取配置项名称
	public function name(){
		$details = $this->details();
		return $details['name'];			
	}
	
	//获取配置项 config_id
	public function id(){
		$details = $this->details();
		return $details['config_id'];			
	}	
	
	//获取配置属性
	public function attrs($key=NULL){
		$details = $this->details();
		if(is_null($key)){
			return $details['attrs'];
		}else{
			return $details['attrs'][$key];
		}
		
	}
	//获取指定identifier所有节点
	public function nodes($attrs=false){
		return $this->get_config_nodes($this->identifier,$attrs);
	}
	
	//获取指定identifier所有节点 序列化config_id为数组键
	public function seqNodes($attrs=false){
		$nodes = $this->get_config_nodes($this->identifier,$attrs);
		$seq_nodes = array();
		foreach($nodes as $val){
			$seq_nodes[$val['config_id']] = $val;
		}
		unset($nodes);
		return $seq_nodes;
	}
	
	//获取配置详细信息
	public function details(){
		return $this->get_config_details($this->identifier);
	}
	
	//根据config_id 获取一个配置信息
	private function get_config_details($identifier){
		
		if(empty($identifier)){
			return array();	
		}
		
		if(isset($this->cache[$identifier]['details'])){
			return $this->cache[$identifier]['details'];
		}
		
		//判断是identitier调用还是config_id调用
		if(is_numeric($identifier)){
			$where = "config_id='{$identifier}'";
		}else{
			$where = "identifier='{$identifier}'";	
		}				
		
		$details = $this->where($where)->find();
		if(empty($details)){
			$this->cache[$identifier]['details'] = array();	
			return array();
		}
		$attrs = empty($details['attrs']) ? array() : json_decode($details['attrs'],true);
		$this->cache[$identifier]['details'] = array('identifier'=>$details['identifier'],'name'=>$details['name'],'attrs'=>$attrs,'config_id'=>$details['config_id'],'parent_id'=>$details['parent_id'],'valid_status'=>$details['valid_status']);
		return $this->cache[$identifier]['details'];
	}
	
	
	//根据config identifier 获取一个配置所有节点
	private function get_config_nodes($identifier,$attrs=false){
		
		if(empty($identifier)){
			return array();	
		}
		
		if(isset($this->cache[$identifier]['nodes'])){
			return $this->cache[$identifier]['nodes'];
		}		
		
		list($field,$except) = $attrs ? array(true,false) : array('attrs',true);
		if(is_numeric($identifier)){
			$config_id = $identifier;
		}else{
			$config_id = $this->getConfigIdByIdentifier($identifier);		
		}
        if (is_null($this->order)) {
            $order = 'sort desc,config_id asc';
        }else{
            $order = $this->order.',sort desc,config_id asc';
        }
		$nodes = $this->field($field,$except)->where("parent_id={$config_id}")->order($order)->select();
		if(empty($nodes)){
			$this->cache[$identifier]['nodes'] = array();	
			return array();
		}
		
		//如果需要读取attrs，处理转成数组	
		if($attrs){
			
			foreach($nodes as &$val){
				$val['attrs'] = empty($val['attrs']) ? array() : json_decode($val['attrs'],true);
			}
			unset($val);
		}
		
		$this->cache[$identifier]['nodes'] = $nodes;
		
		return $nodes;
	}	

	//解析提交过来的 配置属性 转成json格式存库。
	public function parseAttrs($keys,$vals){
		
		if(!is_array($keys)) return '';
		
		$attts = array();
		foreach($keys as $k=>$v){
			$key = trim($v);
			if($key==='') continue;
			$attts[$key] = $vals[$k];
		}
		if(empty($attts)){
			return '';	
		}else{
			return json_encode($attts);
		}
		
	}
	
	//根据identifier获取配置config_id
	public function getConfigIdByIdentifier($identifier){
		if(empty($identifier)){
			return false;	
		}
		return $this->where("identifier='{$identifier}'")->getField('config_id');
	}
	
	//递归删除一个配置
	public function configDelete($config_id){
		
		$children = $this->where("parent_id in({$config_id})")->getField('GROUP_CONCAT(config_id) as children');
		if(empty($children)){
			return true;	
		}
		$this->where("config_id in({$children})")->delete();
		$this->configDelete($children);
				
	}
	
	//检测配置identifier是否存在
	public function checkIdentifier($identifier){
		return $this->where("identifier='{$identifier}'")->count() ? true : false;
	}
	
	//获取指定config_id的父级parent_id
	public function getParentIdByConfigId($config_id){
		return $this->where("config_id={$config_id}")->getField('parent_id');
	}
	
	//递归获取指定parent_id的 所有子节点
	public function getSubNodes($parent_id,$level=1){
				
		$nodes = $this->where("parent_id={$parent_id}")->order('sort desc,config_id asc')->select();
		
		foreach($nodes as &$node){
			$node['_level'] = $level;
			$node['nodes'] = call_user_func(__METHOD__,$node['config_id'],$level+1);
			$node['attrs'] = empty($node['attrs']) ? array() : json_decode($node['attrs'],true);
		}
		
		unset($node);
		
		return $nodes;
	}
	
//
public function getContextMenu(){

$permit = array(array('add'=>power('add'),'edit'=>power('edit'),'delete'=>power('delete')),array('tonavi'=>power('tonavi')));	
$jscode = array(array(),array());

$jscode[0]['lb'] = '[';

if($permit[0]['add']){
$jscode[0]['add'] = <<<JAVASCRIPT
	 {
        name: "新增配置",
		icon:'config-add',
        handler: function() {
           var selectedNode = zTree.getNodeByTId($(this.target).parent('li').prop('id'));
		   zTree.selectNode(selectedNode);
		   addNavi(selectedNode);
        }
    }
JAVASCRIPT;
}

if($permit[0]['edit']){
$jscode[0]['edit'] = <<<JAVASCRIPT
	,{
        name: "编辑配置",
		icon:'config-edit',
        handler: function() {
			var selectedNode = zTree.getNodeByTId($(this.target).parent('li').prop('id'));
			zTree.selectNode(selectedNode);
			zCall.onClick('zTreeContextMenu','tree',selectedNode);
        }
    }
JAVASCRIPT;
}

if($permit[0]['delete']){
$jscode[0]['delete'] = <<<JAVASCRIPT
	,{
        name: "删除配置",
		icon:'config-delete',
        handler: function() {
			var selectedNode = zTree.getNodeByTId($(this.target).parent('li').prop('id'));
			zTree.selectNode(selectedNode);
			dialog.confirm('<strong>确定要此栏目删除吗？</strong><br />如有子集栏目也将一起删除！',function(){
				deleteNavi(selectedNode);
			})
        }
    }
JAVASCRIPT;
}

$jscode[0]['rb'] = ']';

$jscode[1]['lb'] = '[';

if($permit[1]['tonavi']){
$jscode[1]['tonavi'] = <<<JAVASCRIPT
	{
        name: "生成栏目",
		icon:'config-tonavi',
        handler: function() {
			var selectedNode = zTree.getNodeByTId($(this.target).parent('li').prop('id'));
			zTree.selectNode(selectedNode);
			zCall.onClick('zTreeContextMenu','tree',selectedNode);
			createToNavi(selectedNode.id);
        }
    }
JAVASCRIPT;
}
$jscode[1]['rb'] = ']';	

$powers = '[';
foreach($jscode as $item){
	if(count($item)>2){
		$powers .= implode($item).',';
	}

}

return rtrim($powers,',').']';
		
}
	
	
	
		
	
}