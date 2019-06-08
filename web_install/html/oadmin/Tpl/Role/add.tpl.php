<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加角色</title>
{:W('Css','global_widget')}
{:W('Skin','widget')}
<style type="text/css">
html,body{ overflow:hidden;width:100%;height:100%;}
</style>
{:W('jQuery')}
{:W('Dialog')}
{:W('Ztree')}
<script type="text/javascript" >
	var zTree,zCall = {}
	treeSetting = {
			view: {
				selectedMulti: false
			},
			check: {
				enable: true
			},			
			async: {
				enable: true,
				url:'__APP__/role/load',
				autoParam:["id"],
			},
			callback:zCall
			
	}


	$(function(){
		$.fn.zTree.init($("#tree"), treeSetting);
		zTree = $.fn.zTree.getZTreeObj("tree");
		
	});
	
	zCall.onAsyncSuccess = function(event,treeId,treeNode){
		if(treeNode){
			loadAsyncChildNodes(treeNode.children);
		}else{
			loadAsyncChildNodes(zTree.getNodes());
		}
	}

	function loadAsyncChildNodes(nodes){
		for(var i=0;i<nodes.length;i++){
			zTree.reAsyncChildNodes(nodes[i], "refresh", true);
		}
	}



$(function(){
 
 $('#submit').click(function(){
	 
	 var selectedNodes = zTree.getCheckedNodes(true),navis = [];
	 for(var i=0;i<selectedNodes.length;i++){
		navis.push(selectedNodes[i].id);
	 }
		
	var role_name = $.trim($("#role_name").val());
	if(role_name.length==0){
		dialog.tips('error','请输入角色名称');
		return false;	
	}
	
	$.ajax({
		
		 url:'__APP__/role/add_post',
		 type:'POST',
		 data:{role_name:role_name,navis:navis},
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
 	
})	
</script>
</head>

<body>

	<div class="widget-com-tree cf-a">
    	<div class="tree-container  f-l"><ul id="tree" class="ztree"></ul></div>
        <div class="tree-details f-r">
       	  <div class="container" style="padding-top:40px;">
			<div class="oaui-form">
	        	<table width="100%" align="center" border="0">
			    <tr>
			      <td width="200" align="right">角色名称：</td>
			      <td><input type="text" class="oaui-input-text" id="role_name" value="" size="38" /></td>
			    </tr>
	            <tr>
                	<td>&nbsp;</td>
	            	<td><input type="button" id="submit" class="oaui-button oaui-button-submit" value=" 提 交 " /></td>
	            </tr>
			  </table>
             </div>
		 </div>	
        </div>
    </div>
    

</body>
</html>