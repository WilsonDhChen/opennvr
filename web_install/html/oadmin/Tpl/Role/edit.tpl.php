<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>编辑角色</title>
{:W('Css','global_widget')}
{:W('Skin','widget')}
<style type="text/css">
html,body{ overflow:hidden;width:100%;height:100%;}
</style>
{:W('jQuery')}
{:W('Dialog')}
{:W('Ztree')}
<script type="text/javascript" >
	var zTree,zCall = {},zChecked = <?php echo json_encode($role['navis'])?>;
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
	
	
zCall.onAsyncSuccess = function(){
	expandNodesAsync(zTree.getNodes());

}

function expandNodesAsync(nodes){
	
	for(var i=0;i<nodes.length;i++){
		if( $.inArray(nodes[i].id,zChecked)>-1 ){
			zTree.checkNode(nodes[i],true,false);
			nodes[i].checkedOld = true;
		}
		zTree.expandNode(nodes[i], true, false, false);
		
		if(nodes[i].isParent && nodes[i].zAsync){
			expandNodesAsync(nodes[i].children);
		}		
	}	
	
}



$(function(){
 
 $('#submit').click(function(){
	 
	 var changedNodes = zTree.getChangeCheckedNodes(),navis = {};
	  navis.add = [];navis.del = [];
	 for(var i=0;i<changedNodes.length;i++){
		if(changedNodes[i].checked){
			navis.add.push(changedNodes[i].id);	
		}else{
			navis.del.push(changedNodes[i].id);		
		}
		
	 }
	var role_name = $.trim($("#role_name").val());
	if(role_name.length==0){
		dialog.tips('error','请输入角色名称');
		return false;	
	}
	
	$.ajax({
		
		 url:'__APP__/role/edit_post',
		 type:'POST',
		 data:{role_id:<?php echo $role_id;?>,role_name:role_name,add:navis.add,del:navis.del},
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
			      <td><input type="text" class="oaui-input-text" id="role_name" value="{$role.role_name}" size="38"  /></td>
			    </tr>
	            <tr>
                	<td>&nbsp;</td>
	            	<td>
                    	<input type="button" class="oaui-button oaui-button-submit" id="submit" value=" 提 交 " />
                        <hr class="oaui-space oaui-space-x10"/>
                    	<input type="button" class="oaui-button oaui-button-common" onclick="window.history.back()" value=" 返 回 " />
                    </td>
	            </tr>
			  </table>
            </div>	
		 </div>	
        </div>
    </div>
    

</body>
</html>