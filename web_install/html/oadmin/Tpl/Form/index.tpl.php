<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>列表-{$form.name}</title>
{:W('Css','global_widget')}
<style type="text/css">
body{ padding:36px 0 40px 0;}
.grid-btns{height:36px;position:fixed;top:0;left:0;right:0;background: #e9ebed;border-bottom: 1px solid #cccccc;}
.grid-btns .btns-bar li{float:left; display:inline; margin:2px;}
.grid-btns .btns-bar a{ display:block; height:30px; line-height:30px; width:80px; background:linear-gradient(#f0f0f0,#e0e0e0); border-radius:3px; border:1px solid #ddd; text-align:center; color:#666;}
.grid-btns .btns-bar a:hover{background:linear-gradient(#e0e0e0,#d0d0d0); border:1px solid #ccc; text-align:center; color:#555;}
.grid-btns .btns-bar .search-switch{ float:right;}
.grid-btns .btns-bar .search-switch i{font-size:18px;line-height:20px;font-weight:bold;}
.widget-com-grid .grid-search{ position:absolute; padding:10px; background:#e9ebed; top:36px;border-bottom: 1px solid #cccccc;border-top: 1px dotted #ddd; height:auto;min-height:30px;}
.formui-searchui-item{ display:inline-block; margin:0 6px 6px 0;}
.formui-searchui-text{ height:22px; line-height:22px;font-family:'Microsoft Yahei'; color:#333; text-indent:6px;border-color: #d0d0d0;border-style: solid;border-width: 1px;}
.formui-searchui-text:focus{border-color: #AAAAAA #CCCCCC #CCCCCC #AAAAAA;}
.formui-searchui-button{ height:24px; min-width:80px;}
.formui-record-id{ text-align:center;}
.formui-record-value{display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap; max-width:260px;min-width:30px;}
.formui-record-time{ text-align:center; color:#666;}
.formui-record-tips{ color:#ccc;}
.formui-record-colorshow{ color:#ccc;}
</style>
{:W('jQuery')}
{:W('Dialog')}
<script type="text/javascript">
$(function(){
	//{//记录隔行换色}
	$('.widget-com-grid .grid-list tr:even').addClass('even');
	//{//记录双击高亮色}
	$('.widget-com-grid .grid-list tbody tr').dblclick(function(){
		if($(this).hasClass('selected')){
			$(this).removeClass('selected')
		}else{
			$(this).addClass('selected')	
		}
	});
	
})

</script>
<notempty name="has_datepicker">
{:W('Calendar')}
</notempty>
<notempty name="search_fields">
<script type="text/javascript">
$(function(){

	$('#searchSwitch').click(function(){
		if($('.grid-search').is(':hidden')){
			$('.grid-search').slideDown('fast');
		}else{
			$('.grid-search').slideUp('fast');
		}
		
	})
})

function FormSelectEventChange(select,config_id,from){
	var $select = $(select);
	//如果selectholder 选项option 移除所有子项select
	if($select.val()=='#selectholder#'){
		$select.nextAll('select').remove();
		return true;
	}
	//{//如果是multiple多选 或是没有子集  直接跳出}
	if($select.prop('multiple') || $select.data('unrelated') ){
		return true;
	}
		
	
	$.ajax({
		type:'POST',
		data:{ajax:'select',config_id:config_id,field_value:$select.val(),from : from || ''}
	}).done(function(response){
		if(response.status=='unrelated'){
			$select.data('unrelated',true);
			return ;
		}
		
		if(response.status=='none'){
			$select.nextAll('select').remove();
			return ;	
		}
		
		if(response.status=='related'){
			var field_name = $select.prop('name');
			if(field_name.indexOf('[]')===-1){
				field_name+='[]';
				$select.prop('name',field_name);
			}
			var $sub = $(response.data);
			var sub_id = $sub.prop('id');
			$sub.prop('name',field_name);
			$select.nextAll('select').remove();	
			
			$select.after($sub);
			//当前 select追加到dom后 立刻触发一次 change事件
			$('#'+sub_id).trigger('change');
			return ;
		}
	})
	
}
</script>
</notempty>

<if condition="$power_record">
<script type="text/javascript">
var Record = (function(){
	
	return {
		
		delete:function(record_id){
			if(!record_id){
				dialog.tips('error','参数丢失');
				return false;	
			}
			//
			dialog.confirm('确认要删除此条记录吗？',function(){
				$.ajax({
					
					 url:'__URL__/<?php echo $form['sign']?>_delete',
					 type:'POST',
					 data:{record_id:record_id},
					 dataType:'json',
					 beforeSend:function(){
						dialog.loading.show();	 
					},
					complete:function(){
						dialog.loading.hide();
					},
					success:function(response){
						 dialog.tips(response.status,response.info,function(){
							 if(response.status=='success'){
								window.location.reload();					
							}				 
							 
						});
						
					},
					error:function(){
							dialog.tips('error','网络故障 请重试');	
					}	
				})		
			})			
		}	
	};
		
})()
</script>
</if>
</head>

<body>

<div class="widget-com-grid">
<div class="grid-btns">
	<ul class="btns-bar cf-a">
    	<if condition="power($form['sign'].'_insert')">
    	<li><a href="__URL__/{$form.sign}_insert">新 增</a></li>
        </if>
        <notempty name="search_fields">
        <li class="search-switch"><a href="javascript:;" id="searchSwitch" title="打开搜索项">搜索<i>&#8744;</i></a></li>
        </notempty>
    </ul>
    <notempty name="search_fields">
	<div class="grid-search dp-n">
		<form>
			<volist name="search_fields" id="field">
            	{$field.html}
            </volist>
	        <input type="hidden" name="search_request"  value="1" >
	        <input type="submit" class="formui-searchui-button formui-searchui-submit" value=" 查询搜索 " >
	        <notempty name="search_request">    
	        <input type="button" value=" 退出搜索 " class="formui-searchui-button" onclick="window.location='__URL__/{$form.sign}_index'" />
	        </notempty>
	    </form>
	</div>
    </notempty>
</div>
<table class="grid-list">
	<thead>
    	<tr>
        	<notempty name="form['attrs']['record_id']">
            <th><?php echo (!is_numeric($form['attrs']['record_id']) || strlen($form['attrs']['record_id'])>1 ) ? $form['attrs']['record_id'] : '记录编号'; ?></th>
            </notempty>
			<volist name="gridview_fields" id="field">
            <th<?php echo $field['size'].$field['align'].$field['vlign']?>>{$field.name}</th>
            </volist>
            
        	<notempty name="form['attrs']['insert_time']">
            <th><?php echo (!is_numeric($form['attrs']['insert_time']) || strlen($form['attrs']['insert_time'])>1 ) ? $form['attrs']['insert_time'] : '添加时间'; ?></th>
            </notempty> 
            
        	<notempty name="form['attrs']['update_time']">
            <th><?php echo (!is_numeric($form['attrs']['update_time']) || strlen($form['attrs']['update_time'])>1 ) ? $form['attrs']['update_time'] : '更新时间'; ?></th>
            </notempty>                        
            
            <if condition="$power_record">
            <th align="center">记录操作</th>
            </if>
        </tr>
    </thead>
    <tbody>
    	<volist name="gridview_records" id="record">
    	<tr>
            <notempty name="form['attrs']['record_id']">
            <td><p class="formui-record-id"><?php echo $record['record_id']?></p></td>
            </notempty>        
        	<volist name="gridview_fields" id="field">
            <?php $record_field_vars = $FormModel->parseRecordFieldVars($record[$field['key']],$field['type'],$field['item']); ?>
    		<td<?php echo $field['size'].$field['align'].$field['vlign']?> class="formui-record-td" title="<?php echo $record_field_vars['title']?>"><p class="formui-record-value" <?php echo empty($field['style'])?'':$field['style']; ?> ><?php echo $record_field_vars['html']?></p></td>
            </volist>
        	<notempty name="form['attrs']['insert_time']">
            <td><p class="formui-record-time"><?php echo date('Y-m-d H:i',$record['insert_time'])?></p></td>
            </notempty> 
            
        	<notempty name="form['attrs']['update_time']">
            <td><p class="formui-record-time"><?php echo empty($record['update_time'])?'<span class="formui-record-tips">暂未更新</span>':date('Y-m-d H:i',$record['update_time'])?></p></td>
            </notempty>             
            <if condition="$power_record">
            <td align="center">
                <if condition="$power_detail">
                <a href="__URL__/{$form.sign}_detail?record_id=<?php echo $record['record_id'];?>" title="查看详情" class="grid-func func-details">详情</a>
                </if>
                <if condition="$power_update">
                <a href="__URL__/{$form.sign}_update?record_id=<?php echo $record['record_id'];?>" title="修改记录" class="grid-func func-edit">修改</a>
                </if> 
                <if condition="$power_delete">
                <a href="javascript:;" onclick="Record.delete('<?php echo $record['record_id'];?>')" title="删除记录" class="grid-func func-delete">删除</a>
                </if>                                             	
            </td>
            </if>           
    	</tr>
        </volist>
        <empty name="gridview_records">
        <tr>
            <td colspan="99" align="center"><notempty name="search_request">没有找到符合搜索条件的数据记录<else />暂无任何记录数据记录</notempty></td>
        </tr>
        </empty>        
    </tbody>
</table>
<div class="grid-page">
{$page_html}
</div>
</div>
</body>
</html>