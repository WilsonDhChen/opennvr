<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>角色列表</title>
{:W('Css','global_widget')}
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

function role_delete(role_id){
	
	if(!role_id){
		dialog.tips('error','参数丢失');
		return false;	
	}
	//
	dialog.confirm('确认要删除此权限角色吗？<br/>删除后此角色下员工将无法正常使用本系统',function(){
		$.ajax({
			
			 url:'__APP__/role/delete_post',
			 type:'POST',
			 data:{role_id:role_id},
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
</script>
</head>

<body>
<div class="widget-com-grid">
<table class="grid-list">
	<thead>
    	<tr>
    		<th>角色名称</th>
            <th>人数</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    	<?php foreach($grid_data as $vo):?>
    	<?php if( !in_array(C('SUPER_ADMIN_ROLE'),$staff['roles'])  ):?>
        	<?php if( C('SUPER_ADMIN_ROLE')==$vo['role_id'] ):?>
				<?php continue; ?>
            <?php endif;?>
        <?php endif;?>
        <tr>        	    		
            <td>{$vo.role_name}</td>
            <td align="center">{$vo.role_count}</td>            
            <td>{$vo.insert_time|date='Y-m-d H:i',###}</td>
            <td>
            <if condition="C('SUPER_ADMIN_ROLE') neq $vo['role_id']">
                <if condition="power('edit')">
            	<a href="__APP__/role/edit?role_id={$vo['role_id']}" title="编辑" class="grid-func func-edit">编辑</a>
                </if>
                <if condition="power('delete')">
                <a href="javascript:;" title="删除" onclick="role_delete({$vo.role_id})" class="grid-func func-delete">删除</a>
                </if>
            </if>
            </td>
    	</tr>
        <?php endforeach;?>
    </tbody>
</table>
<div class="grid-page">
{$page_html}
</div>
</div>
</body>
</html>