<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$cnname}</title>
{:W('Css','global_widget')}
<style type="text/css">
.grid-tool{ height:30px; background:#fbfbfb; padding:4px; text-align:right;}
.grid-tool-button{display:inline-block; vertical-align:middle; height:28px; line-height:28px; text-align:center; min-width:80px; background:#555; border:1px solid #333; color:#fff; border-radius:2px; margin-left:16px; box-sizing:content-box;}
.grid-tool-button:hover{ background:#444; border:1px solid #222;}
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
	//{//全选}
	$('#selectPicker').change(function(){
		$('.select-item').prop({checked:$(this).prop('checked')})
	})
})
</script>

<if condition="power($idname.'__delete')">
<script type="text/javascript">
function config_delete(id){
	
	if(id){
		var data = {id:id} 
		var tips = '确定要删除此条记录吗?';
	}else{
		var tips = '确定要删除选中记录吗?';
		var data = $('#OAForm').serialize();
		if(!data){
			dialog.tips('warning','请先选择要删除的记录');
			return false;
		}
	}
	dialog.confirm(tips,function(){
		$.ajax({
			 url:'__URL__/<?php echo $idname?>__delete',
			 type:'POST',
			 data:data,
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
</script>
</if>
</head>

<body>
	<div class="widget-com-grid">
    	<div class="grid-tool">
        	<if condition="power($idname.'__insert')">
            <a href="__URL__/{$idname}__insert/" class="grid-tool-button" title="添加记录" >＋添加记录</a>
            </if>
            <if condition="power($idname.'__delete')">
            <input type="button" onclick="config_delete()" class="grid-tool-button" title="批量删除" value="－批量删除" />
            </if>
        </div>
		<table class="grid-list">
			<thead>
		    	<tr>
                	<if condition="power($idname.'__delete')">
		    		<th width="36"><input type="checkbox" id="selectPicker" /></th>
                    </if>
                    <notempty name="attrs['config_id']">
                    <th>ID</th>
                    </notempty>
                    <th>{$attrs.config_name|default='记录名称'}</th>
                    <if condition="spower($idname.'__identifier') && !empty($attrs['identifier_name'])">
		            <th>{$attrs.identifier_name}</th>
                    </if>
                    <foreach name="attrs['config_fields']" item="field">
                    <th><?php echo isset($attrs['field_'.$field]) ? $attrs['field_'.$field] :'&nbsp;'; ?></th>
                    </foreach>
					<notempty name="attrs['config_sort']">
					<th>记录排序</th>
					</notempty>
					<notempty name="attrs['config_time']">
                    <th>添加时间</th>
					</notempty>
		            <th>记录操作</th>
		        </tr>
		    </thead>
		    <tbody>
            	<form id="OAForm">
				<volist name="pagelist" id="vo">
		        <tr> 
                	<if condition="power($idname.'__delete')">
                	<td align="center"><input type="checkbox" class="select-item" name="id[]" value="{$vo.config_id}" /></td>
                    </if>
		            <notempty name="attrs['config_id']">
                    <td align="center">{$vo.config_id}</td>
                    </notempty>
                    <td><strong>{$vo.name}</strong></td>
                    <if condition="spower($idname.'__identifier') && !empty($attrs['identifier_name'])">
		            <td align="center">{$vo.identifier|default='&nbsp;'}</td>
                    </if>
                    <foreach name="attrs['config_fields']" item="field">
                    <td><?php echo isset($vo['attrs'][$field]) ? $vo['attrs'][$field] :'&nbsp;'; ?></td>
                    </foreach>
                    <notempty name="attrs['config_sort']">
                    <td align="center">{$vo.sort}</td>
                    </notempty>
                    <notempty name="attrs['config_time']">
		            <td align="center">{$vo.insert_time|date='Y-m-d H:i:s',###}</td>
                    </notempty>
		            <td align="center">
		                <if condition="power($idname.'__detail')">
		            	<a href="__URL__/{$idname}__detail/?id={$vo['config_id']}" title="详情" class="grid-func func-details">详情</a>
		                </if>
		                <if condition="power($idname.'__update')">
		            	<a href="__URL__/{$idname}__update/?id={$vo['config_id']}" title="修改" class="grid-func func-edit">修改</a>
		                </if>
		                <if condition="power($idname.'__delete')">
		                <a href="javascript:;" title="删除" onclick="config_delete({$vo.config_id})" class="grid-func func-delete">删除</a>
		                </if>
		            </td>
		    	</tr>
		        </volist>
                </form>
		    </tbody>
		</table>
		<div class="grid-page">{$pagehtml}</div>
	</div>
</body>
</html>