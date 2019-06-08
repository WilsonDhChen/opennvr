<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>系统栏目列表</title>
{:W('Css','global_widget')}
{:W('Skin','widget')}
<style type="text/css">
html,body{ overflow:hidden;width:100%;height:100%;}
#navi_parent{ color:#333; font-weight:bold;}
.taw-1{width:364px; height:64px;}
.itw-1{width:362px;}
.itw-2{width:50px;}
.oaui-contextmenu-icon{	background-image:url(__STATIC__/image/contextmenu_icon_section.png);}
.oaui-contextmenu-icon-section-add{ background-position:0 0;}
.oaui-contextmenu-icon-section-edit{background-position:-28px 0;}
.oaui-contextmenu-icon-section-delete{background-position:-56px 0;}
</style>
{:W('jQuery')}
{:W('Dialog')}
{:W('Ztree')}
{:W('ContextMenu')}
<script type="text/javascript" >
	var zTree,zCall = {};
	treeSetting = {
			view: {
				selectedMulti: false
			},
			async: {
				enable: true,
				url:'__APP__/section/load',
				autoParam:["id"],
			},
			callback: zCall
	}


	$(function(){
		$.fn.zTree.init($("#tree"), treeSetting);
		zTree = $.fn.zTree.getZTreeObj("tree");
		
	});


zCall.onClick = function(event,treeId,treeNode){
	
	if(!treeNode || !$.isNumeric(treeNode.id)){
		return false;	
	}
	
	var nodeData = $('#'+treeNode.tId+'_a').data('nodeData');
	if(nodeData){
		displayNaviInfo(nodeData);
	}else{
		$.ajax({
			url:'__APP__/section/get_navi',
			type:'POST',
			data:{navi_id:treeNode.id},
			dataType:'json',
			success:function(response){
				if(response.status=='success'){
					//缓存栏目数据
					$('#'+treeNode.tId+'_a').data('nodeData',response.data);
					//显示栏目数据
					displayNaviInfo(response.data);
				}else{
					dialog.tips('error',response.info);
				}	
			},
			error:function(){
				dialog.tips('error','网络故障 请重试');	
			}
		})
	}
	

}


function displayNaviInfo(data){
	$('#navi_parent').html(data.parent_name+'('+data.parent_id+')');
	$("input[name='parent_id']").val(data.parent_id);
	$("input[name='navi_id']").val(data.navi_id);
	$("input[name='navi_name']").val(data.navi_name);
	$("input[name='module']").val(data.module);
	$("textarea[name='action']").val(data.action);
	$("input[name='get_params']").val(data.get_params);
	$("input[name='sort']").val(data.sort);
	$("textarea[name='conditions']").val(data.conditions);
	
	//
	$(":radio[name='valid_status']").prop({checked:false})
	$("#valid_status_"+data.valid_status).prop({checked:true})
	
	
}

function addNavi(parentNode){
	$('#navi_parent').html(parentNode.name+'('+parentNode.id+')');
	$("input[name='parent_id']").val(parentNode.id);
	$("input[name='navi_id']").val(0);
	$("input[name='navi_name']").val('');
	$("input[name='module']").val('');
	$("textarea[name='action']").val('');
	$("input[name='get_params']").val('');
	$("input[name='sort']").val(0);
	$("textarea[name='conditions']").val('');
	
	//
	$(":radio[name='valid_status']").prop({checked:false});
	$("#valid_status_1").prop({checked:true});
}

function deleteNavi(node){
	
	$.ajax({
		
		 url:'__APP__/section/navi_delete',
		 type:'POST',
		 data:{navi_id:node.id},
		 dataType:'json',
		 beforeSend:function(){
			dialog.loading.show();	 
		},
		complete:function(){
			dialog.loading.hide();
		},
		 success:function(response){
			if(response.status=='success'){
				var node = zTree.getSelectedNodes()[0];
				$('#'+node.tId+'_a').removeData('nodeData');
				zTree.reAsyncChildNodes(node.getParentNode(), "refresh");
			}		 
			dialog.tips(response.status,response.info);
			
		},
		error:function(){
				dialog.tips('error','网络故障 请重试');	
		}	
	})
				
	
}


zCall.onRightClick = function(event, treeId, treeNode){
	
	if (!treeNode && event.target.tagName.toLowerCase() != "button" && $(event.target).parents("a").length == 0) {
		zTree.cancelSelectedNode();
	} else if (treeNode && !treeNode.noR) {
		zTree.selectNode(treeNode);
	}

}


<?php if(power('add') || power('edit') || power('navi_delete')):?>
$(function(){
	
var zTreeContextMenu = [
    [	
	 <?php if(power('add')):?>
	 {
        name: "新增栏目",
		icon:'section-add',
        handler: function() {
           var selectedNode = zTree.getNodeByTId($(this.target).parent('li').prop('id'));
		   zTree.selectNode(selectedNode);
		   addNavi(selectedNode);
        }
    }
	<?php if(power('edit')) echo ',';?>
	<?php endif;?>
	
	 <?php if(power('edit')):?>
	{
        name: "编辑栏目",
		icon:'section-edit',
        handler: function() {
			var selectedNode = zTree.getNodeByTId($(this.target).parent('li').prop('id'));
			zTree.selectNode(selectedNode);
			zCall.onClick('zTreeContextMenu','tree',selectedNode);
			
        }
    }
	<?php endif;?>
	]
	
	<?php if(power('navi_delete')):?>
	,
    [{
        name: "删除栏目",
		icon:'section-delete',
        handler: function() {
			var selectedNode = zTree.getNodeByTId($(this.target).parent('li').prop('id'));
			zTree.selectNode(selectedNode);
			dialog.confirm('<strong>确定要此栏目删除吗？</strong><br />如有子集栏目也将一起删除！',function(){
				 deleteNavi(selectedNode);
			})
        }
    }]
	<?php endif;?>
];	
		
		
contextMenu('ztree',zTreeContextMenu,{proxy:'#tree',context:"a[id^='tree_']"});	
	
})
<?php endif;?>

$(function(){
 
 $('#submit').click(function(){
		
	var navi_name = $.trim($("input[name='navi_name']").val());
	if(navi_name.length==0){
		dialog.tips('error','请输入栏目名称');
		return false;	
	}
	
	var post_url;
	var navi_id = $("input[name='navi_id']").val();
	if( navi_id == '0'){
		post_url = '__APP__/section/navi_add_post';
	}else{
		post_url = '__APP__/section/navi_edit_post';
	}
	
	$.ajax({
		
		 url:post_url,
		 type:'POST',
		 data:$('#form').serialize(),
		 dataType:'json',
		 beforeSend:function(){
			dialog.loading.show();	 
		},
		complete:function(){
			dialog.loading.hide();
		},
		 success:function(response){
			 if(response.status=='success'){

				if(navi_id == '0'){
					var node = zTree.getSelectedNodes()[0]; 
					if(node){
						node.isParent = true;
					}
					zTree.reAsyncChildNodes(node,"refresh");
				}else{
					var node = zTree.getSelectedNodes()[0];
					$('#'+node.tId+'_a').removeData('nodeData');
					zTree.reAsyncChildNodes(node.getParentNode(), "refresh");
				}
							
			}			 
			dialog.tips(response.status,response.info);
			
		},
		error:function(){
				dialog.tips('error','网络故障 请重试');	
		}	
	})
		 
	 
})
 	
})	
</script>
</head>

<body>

	<div class="widget-com-tree">
    	<div class="tree-container">
        	<ul id="tree" class="ztree"></ul>
        </div>
        <div class="tree-details">
       	  <div class="container">
            	<form id="form">
	        	<table width="100%" align="center" border="0">
			    <tr>
			      <td width="100">栏目父级：</td>
			      <td><span id="navi_parent">顶级栏目</span><input type="hidden" name="parent_id" value="0" /><input type="hidden" name="navi_id" value="0" /></td>
			    </tr>
			    <tr>
			      <td>栏目名称：</td>
			      <td><input type="text" name="navi_name" value="" /></td>
			    </tr>
			    <tr>
			      <td>栏目模块：</td>
			      <td><input type="text" name="module" value="" /></td>
			    </tr>
			    <tr>
			      <td>栏目操作：<br />(多个逗号分隔)</td>
			      <td><textarea name="action"  class="taw-1"></textarea></td>
			    </tr>
			    <tr>
			      <td>权限表达式：<br />(php语法)</td>
			      <td><textarea name="conditions"  class="taw-1"></textarea></td>
			    </tr>                 
			    <tr>
			      <td>Params：<br />(GET参数)</td>
			      <td><input type="text" class="itw-1" name="get_params" value="" /></td>
			    </tr>
			    <tr>
			      <td>栏目排序：<br />(降序)</td>
			      <td><input type="text" class="itw-2" name="sort" value="0" /></td>
			    </tr>                                
            	<tr>
	              <td>状态：</td>
	              <td>
	                  <input type="radio" name="valid_status" value="1" id="valid_status_1" checked="checked"/><label for="valid_status_1">正常</label> 　
	                  <input type="radio" name="valid_status" value="0" id="valid_status_0"/><label for="valid_status_0">锁定</label>　
	              </td>
           		</tr>
				<?php if(power('add') || power('edit')):?>
		        <tr>
		            <td>&nbsp;</td>
		            <td><input type="button" id="submit" class="oaui-button oaui-button-submit" value=" 提 交 " /></td>
		        </tr>
		        <?php endif;?>
				</table>
            </form>
		 </div>
        </div>
    </div>
    

</body>
</html>