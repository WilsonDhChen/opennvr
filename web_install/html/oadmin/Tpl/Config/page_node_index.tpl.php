<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$cnname}</title>
{:W('Css','global_widget')}
<style type="text/css">
.widget-com-grid .grid-list td{padding:0;}
.level-space{ color:#ccc; border-bottom:1px dotted #333; line-height:27px; display:inline-block; height:27px; margin-bottom:-1px;}
.level-num{ color:#999;}
.node-add-button{ border:none; width:16px; height:16px; visibility:hidden; overflow:hidden; text-indent:-99em; padding:0; margin:0 0 0 5px; box-sizing:content-box; background:url(__STATIC__/image/icon_insert.png) no-repeat center center;}
</style>
{:W('jQuery')}
{:W('Dialog')}
<script type="text/javascript">
$(function(){
	
	//{//鼠标经过高亮色}
	$('.widget-com-grid .grid-list tbody tr').hover(function(){
		$(this).addClass('selected');
		$(this).find('.node-add-button').css('visibility','visible');
	},function(){
		$(this).removeClass('selected');
		$(this).find('.node-add-button').css('visibility','hidden');
	});
	
	//{//添加子级}
	$('.node-add-button').click(function(){
		var pid = $(this).data('id');
		dialog.frame('__URL__/{$idname}__insert?pid='+pid,'添加子级',true);
	});
	
	//aforiframe
	$('.aforiframe').click(function(){
		dialog.frame($(this).prop('href'),$(this).prop('title'),true);
		return false;	
	})

})
</script>
<if condition="power($idname.'__delete')">
<script type="text/javascript">
function config_delete(config_id){
	
	if(!config_id){
		dialog.tips('warning','参数丢失');
		return false;
	}
	dialog.confirm('确定要删除此节点以及节点下所有子级吗?<br>',function(){
		$.ajax({
			 url:'__URL__/<?php echo $idname?>__delete',
			 type:'POST',
			 data:{config_id:config_id},
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
		<table class="grid-list">
			<thead>
		    	<tr>
                    <th width="40">层级</th>
                    <th>{$attrs.config_name|default='记录名称'}</th>
                    <if condition="spower($idname.'__identifier') && !empty($attrs['identifier_name'])">
		            <th>{$attrs.identifier_name}</th>
                    </if>
                    <foreach name="attrs['config_fields']" item="field">
                    <th><?php echo isset($attrs['field_'.$field]) ? $attrs['field_'.$field] :'&nbsp;'; ?></th>
                    </foreach>
					<notempty name="attrs['config_sort']">
                    <th width="100">记录排序</th>
                    </notempty>
		            <th width="120">记录操作</th>
		        </tr>
		    </thead>
		    <tbody>
            	<form id="OAForm">
                <tr>
                    <td align="center"><span class="level-num">0</span></td>
                    <td>
                        <span class="level-space">&nbsp;</span>
                    	<strong>{$cnname}</strong>
                        <if condition="power($idname.'__insert')">
                        <gt name="attrs['config_level']" value="0">
                        <button type="button" class="node-add-button" data-id="{$top_config_id}" title="添加子级">+</button>
                        </gt>
                        </if>
                    </td>
                    <if condition="spower($idname.'__identifier') && !empty($attrs['identifier_name'])">
		            <td width="160" align="center">{$idname}</td>
                    </if>
                    <foreach name="attrs['config_fields']" item="field">
                    <td>&nbsp;</td>
                    </foreach>
                    <notempty name="attrs['config_sort']">
                    <td align="center">&nbsp;</td>
                    </notempty>
		            <td align="center">&nbsp;</td>
		    	</tr>
				<?php function recursive_node($nodes,$idname,$attrs){
					  $level++;
					  foreach($nodes as $key=>$vo){
				?>
                
		        <tr> 
                    <td align="center"><span class="level-num">{$vo._level}</span></td>
                    <td>
                        <span class="level-space">{:str_repeat('&nbsp;&nbsp;&nbsp;',$vo['_level'])}</span>
                    	<strong>{$vo.name}</strong>
                        <if condition="power($idname.'__insert')">
                        <lt name="vo['_level']" value="$attrs['config_level']">
                        <button type="button" class="node-add-button" data-id="{$vo.config_id}" title="添加子级">+</button>
                        </lt>
                        </if>
                    </td>
                    <if condition="spower($idname.'__identifier') && !empty($attrs['identifier_name'])">
		            <td width="160" align="center">{$vo.identifier|default='&nbsp;'}</td>
                    </if>
                    <foreach name="attrs['config_fields']" item="field">
                    <td><?php echo isset($vo['attrs'][$field]) ? $vo['attrs'][$field] :'&nbsp;'; ?></td>
                    </foreach>
                    <notempty name="attrs['config_sort']">
                    <td align="center">{$vo.sort}</td>
                    </notempty>
		            <td align="center">
		                <if condition="power($idname.'__detail')">
		            	<a href="__URL__/{$idname}__detail/?id={$vo['config_id']}" title="节点详情" class="grid-func aforiframe func-details">详情</a>
		                </if>
		                <if condition="power($idname.'__update')">
		            	<a href="__URL__/{$idname}__update/?id={$vo['config_id']}" title="修改节点" class="grid-func aforiframe func-edit">修改</a>
		                </if>
		                <if condition="power($idname.'__delete')">
		                <a href="javascript:;" title="删除节点" onclick="config_delete({$vo.config_id})" class="grid-func func-delete">删除</a>
		                </if>
		            </td>
		    	</tr>
		        <?php 
						if($vo['nodes']){
							recursive_node($vo['nodes'],$idname,$attrs);
						}				
					}

				}
				
				recursive_node($nodes,$idname,$attrs);
				?>
                </form>
		    </tbody>
		</table>
	</div>
</body>
</html>