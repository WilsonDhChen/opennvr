<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>系统配置</title>
{:W('Css','global_widget')}
{:W('Skin','widget')}
<style type="text/css">
html,body{ overflow:hidden;width:100%;height:100%;}
#config_parent{ color:#333; font-weight:bold;}
.widget-com-tree .tr-highlight td{ color:#5883d2;}
.widget-com-tree .tr-highlight td input{ border:1px solid #5883d2; width:360px; text-indent:2px;}
.attrs-info{ color:#999;}
.attrs-info span{ padding:0 6px;}
.attrs-info .attrs-key{ padding:0 65px 0 30px;}
.attrs-info .attrs-val{ padding-left:50px;}
.attrs-arrow{ color:#999;}
.attrs-item{margin-bottom:1px;}
.attrs-button-delete{ background-image:url(__STATIC__/image/delete_item.png);}
.attrs-button-insert{ background-image:url(__STATIC__/image/insert_item.png);}
.oaui-contextmenu-icon{	background-image:url(__STATIC__/image/contextmenu_icon_config.png);}
.oaui-contextmenu-icon-config-add{ background-position:0 0;}
.oaui-contextmenu-icon-config-edit{background-position:-28px 0;}
.oaui-contextmenu-icon-config-delete{background-position:-56px 0;}
</style>
{:W('jQuery')}
{:W('Dialog')}
{:W('Ztree')}
{:W('ContextMenu')}
<script type="text/javascript" >

	function resetAttrItem(key,val){
		var item ={};
		item.key = key || '';
		item.val = val || '';
		
		var attr_item = '<li class="attrs-item"><input type="text" name="attrs_key[]" value="'+item.key+'" /> <span class="attrs-arrow">→ </span><input type="text" name="attrs_val[]" value="'+item.val+'" /> <input type="button" value=" " title="新增属性" class="com-button-16px attrs-button-insert" /></li>';
		$('#attrs_list').html(attr_item);
	}
	$(function(){
		resetAttrItem();	
	})
	
	var zTree,zCall = {};
	treeSetting = {
			view: {
				selectedMulti: false
			},
			async: {
				enable: true,
				url:'__APP__/config/load',
				autoParam:["id"],
				otherParam: {"cate":"system"}
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
			url:'__APP__/config/get_config',
			type:'POST',
			data:{config_id:treeNode.id},
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
	

};

function quotesEntities(string, specify = 0) {

    specify = specify || 0;

    switch (specify) {
        case 0 :
            return string.replace(new RegExp("__BACKSLASH__'", 'g'), '&#x27;').replace(new RegExp('__BACKSLASH__"', 'g'), '&#x22;');
            break;
        case 1 :
            return string.replace(new RegExp("__BACKSLASH__'", 'g'), '&#x27;');
            break;
        case 2 :
            return string.replace(new RegExp('__BACKSLASH__"', 'g'), '&#x22;');
            break;
    }

    return string;

}


function displayNaviInfo(data){
	$('#config_parent').html(data.parent_name+'('+data.parent_id+')');
	$("input[name='parent_id']").val(data.parent_id);
	$("input[name='config_id']").val(data.config_id);
	<?php if(power('identifier')):?>
	$("input[name='identifier']").val(data.identifier);
	<?php endif;?>
	$("input[name='name']").val(data.name);
	$("input[name='sort']").val(data.sort);
	if(data.attrs){
		//清空原属性项
		$('#attrs_list').find('li').remove();
		//
		var first_attr = true;
		$.each(data.attrs,function(key,val){
			if(first_attr){
				resetAttrItem(key,val);
				first_attr = false;
			}else{
				$('#attrs_list').append('<li class="attrs-item"><input type="text" name="attrs_key[]" value="'+key+'" /> <span class="attrs-arrow">→ </span><input type="text" name="attrs_val[]" value="'+quotesEntities(val)+'" /> <input type="button" value=" " title="删除属性" class="com-button-16px attrs-button-delete" /></li>');
			}
			
		})
	}else{
		resetAttrItem();
	}

	
	
}

function addNavi(parentNode){
	$('#config_parent').html(parentNode.name+'('+parentNode.id+')');
	$("input[name='parent_id']").val(parentNode.id);
	$("input[name='config_id']").val(0);
	<?php if(power('identifier')):?>
	$("input[name='identifier']").val('');
	<?php endif;?>
	$("input[name='name']").val('');
	$("input[name='sort']").val(0);
    var $attrs_list = $('#attrs_list');
    $attrs_list.find('li:gt(0)').remove();
    $attrs_list.find('li:eq(0)').find('input').val('');

}

function deleteNavi(node){
	
	$.ajax({
		
		 url:'__APP__/config/delete_post',
		 type:'POST',
		 data:{config_id:node.id},
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


<?php if(power('add') || power('edit') || power('delete') || power('tonavi')):?>
$(function(){
	
contextMenu('config',<?php echo $context_menu;?>,{proxy:'#tree',context:"a[id^='tree_']"});

});
<?php endif;?>

$(function(){
 
 $('#submit').click(function(){
	
		
	var navi_name = $.trim($("input[name='name']").val());
	if(navi_name.length==0){
		dialog.tips('error','请输入配置名称');
		return false;	
	}
	
	var post_url;
	var config_id = $("input[name='config_id']").val();
	if(config_id == '0'){
		post_url = '__APP__/config/system_add_post';
	}else{
		post_url = '__APP__/config/system_edit_post';
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
				if(config_id == '0'){
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

$(function(){
	//{//新增配置属性}
	$('#attrs_list').on('click','.attrs-button-insert',function(){
		var html = '<li class="attrs-item"><input type="text" name="attrs_key[]" /> <span class="attrs-arrow">→ </span><input type="text" name="attrs_val[]" /> <input type="button" value=" " title="删除属性" class="com-button-16px attrs-button-delete" /></li>';
		$('#attrs_list').append(html);	
	})
	//{//删除配置属性}
	$('#attrs_list').on('click','.attrs-button-delete',function(){
		$(this).parent().remove();	
	})
	//attrs 多文本辅助输入(使用 Ctrl+单击)
	$('#attrs_list').on('click',':text',function(event){
		if(event.ctrlKey){
			dialog.input(this);		
		}
	})	
	
})
</script>
</head>

<body>

	<div class="widget-com-tree cf-a">
    	<div class="tree-container  f-l"><ul id="tree" class="ztree"></ul></div>
        <div class="tree-details f-r">
       	  <div class="container">
            	<form id="form">
	        	<table width="100%" align="center" border="0">
			    <tr>
			      <td width="100">配置父级：</td>
			      <td><span id="config_parent">系统配置(1)</span><input type="hidden" name="parent_id" value="1" /><input type="hidden" name="config_id" value="0" /></td>
			    </tr>
			    <?php if(power('identifier')):?>
                <tr class="tr-highlight">
			      <td>配置标识：</td>
			      <td><input type="text" name="identifier" value="" /></td>
			    </tr>
                <?php endif;?>
			    <tr>
			      <td>配置名称：</td>
			      <td><input type="text" name="name" value="" /></td>
			    </tr>
				<tr>
			      <td>配置属性：</td>
			      <td>
                  		<p class="attrs-info"><span class="attrs-key">属性键</span><span class="attrs-val">属性值</span></p>
                  		<ul id="attrs_list"></ul>
                  </td>
			    </tr> 
			    <tr>
			      <td>配置排序：</td>
			      <td><input type="text" name="sort" value="0" size="6" /></td>
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