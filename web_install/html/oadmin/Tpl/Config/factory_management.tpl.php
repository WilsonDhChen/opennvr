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
		dialog.frame('__URL__/Factory_insert','添加厂家',true);
	});
	
	//aforiframe
	$('.aforiframe').click(function(){
		var id = $(this).data('id');
		dialog.frame('__URL__/Factory_update?id='+id,$(this).prop('title'),true);
		return false;	
	})

})
</script>
<script type="text/javascript">
function config_delete(id){
	dialog.confirm('确定要删除此厂家么?<br>',function(){
		$.ajax({
			 url:'__URL__/Factory_del',
			 type:'POST',
			 data:{id:id},
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
		    		<th>层级</th> 
                    <th>名称</th> 
                    <th>缩写</th> 
                    <th width="100">排序</th>
		            <th width="120">操作</th>
		        </tr>
		    </thead>
		    <tbody>
            	<form id="OAForm">
                <tr>
                    <td align="center"><span class="level-num">0</span></td>
                    <td>
                        <span class="level-space">&nbsp;</span>
                    	<strong>厂家</strong>
                        <button type="button" class="node-add-button" data-id="" title="添加子级">+</button>
                    </td>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
		            <td align="center">&nbsp;</td>
		    	</tr>
		    	<foreach name="list" item="vo">
		        <tr> 
                    <td align="center"><span class="level-num">1</span></td>
                    <td>
                    	<span class="level-space">&nbsp;</span>
                    	<strong>{$vo.name}</strong>
                    </td>
                    <td align="center">{$vo.py}</td>
                    <td align="center">{$vo.sort}</td>
		            <td align="center">
		            	<a href="javascript:;" title="修改厂家" data-id="{$vo.id}"  class="grid-func aforiframe func-edit">修改</a>
		                <a href="javascript:;" title="删除" onclick="config_delete({$vo.id})" class="grid-func func-delete">删除</a>
		            </td>
		    	</tr>
		     	</foreach>
                </form>
		    </tbody>
		</table>
	</div>
</body>
</html>