<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$cnname}-添加记录</title>
{:W('Css','global_widget')}
{:W('Skin','widget')}
<style type="text/css">
.attrs-info{ color:#999;}
.attrs-info span{ padding:0 6px; display:inline-block;width:260px; vertical-align:middle; text-align:center;}
.attrs-info .attrs-key{ }
.attrs-info .attrs-val{ }
.attrs-arrow{ color:#999;}
.attrs-item{margin-bottom:5px;}
.attrs-button-delete{ background-image:url(__STATIC__/image/delete_item_32.png); vertical-align:top;}
.attrs-button-insert{ background-image:url(__STATIC__/image/insert_item_32.png); vertical-align:top;}
</style>
{:W('jQuery')}
{:W('Dialog')}
<script type="text/javascript" src="__STATIC__/js/jquery.witaform.js"></script>
<script type="text/javascript">

function resetAttrItem(key,val){
	var item ={};
	item.key = key || '';
	item.val = val || '';
	
	var attr_item = '<li class="attrs-item"><input type="text" name="attrs_key[]" class="oaui-input-text" value="'+item.key+'" /> <span class="attrs-arrow">→ </span><input type="text" name="attrs_val[]"  class="oaui-input-text" value="'+item.val+'" /> <input type="button" value=" " title="新增属性" class="com-button-32px attrs-button-insert" /></li>';
	$('#attrs_list').html(attr_item);
}

$(function(){
	
	resetAttrItem('value')

	$('#OAForm').witaForm({
			  
		<?php 
			if(spower($idname.'__identifier') && !empty($identifier_name)){
				echo 'rule:{'.($config_rule?'"config_name":{'.$config_rule.'},':'').($identifier_rule?'"identifier_name":{'.$identifier_rule.'},':'').'"config_sort":{required:true,ptint:true}'."},\n";
			}else{
				echo 'rule:{'.($config_rule?'"config_name":{'.$config_rule.'},':'').'"config_sort":{required:true,ptint:true}'."},\n";	
			}
	    ?>
		submit:function(){
			$(this).find(":submit").prop({disabled:true});
			dialog.loading.show();
		},
		complete:function(){
			$(this).find(":submit").prop({disabled:false});
			dialog.loading.hide();
		},		
		alert:function(tips){
			dialog.alert(tips);
		},
		error:function(){
			dialog.alert('网络故障，请重试');
		},
		success:function(response){
			if(response.status=='success'){
				dialog.confirm('添加记录成功，是否继续添加记录?',function(){
					window.location.reload();	
				},function(){
					window.location = '__URL__/<?php echo $idname?>__index';
				});
			}else{
				dialog.alert(response.info)	
			}				

		}
		
		
	});
	
	//{//新增配置属性}
	$('#attrs_list').on('click','.attrs-button-insert',function(){
		var html = '<li class="attrs-item"><input type="text" name="attrs_key[]" class="oaui-input-text" /> <span class="attrs-arrow">→ </span><input type="text" name="attrs_val[]" class="oaui-input-text" /> <input type="button" value=" " title="删除属性" class="com-button-32px attrs-button-delete" /></li>';
		$('#attrs_list').append(html);	
	})
	//{//删除配置属性}
	$('#attrs_list').on('click','.attrs-button-delete',function(){
		$(this).parent().remove();	
	})	
	
})
</script>
</head>

<body>
   	<form action="__SELF__" id="OAForm" method="post">
        <div class="oaui-form">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
                <if condition="spower($idname.'__identifier') && !empty($identifier_name)">
	        	<tr>
	            	<td width="20%" align="right"><strong>{$identifier_name}</strong>：</td>
	                <td><input type="text" class="oaui-input-text" value="" title="{$identifier_name}" name="identifier_name"  /></td>
	            </tr>                 
                </if>            
	        	<tr>
	            	<td width="20%" align="right"><strong>{$config_name|default='记录名称'}</strong>：</td>
	                <td><input type="text" class="oaui-input-text" value="" title="{$config_name}" name="config_name"  /></td>
	            </tr> 
            	
	        	<tr>
	            	<td width="20%" align="right"><strong>配置属性</strong>：</td>
	                <td>
                    	<p class="attrs-info"><span class="attrs-key">属性键</span><span class="attrs-val">属性值</span></p>
	                    <ul id="attrs_list"></ul>
                    </td>
	            </tr>
				<notempty name="config_sort">
	        	<tr>
	            	<td width="20%" align="right"><strong>排序</strong>：</td>
	                <td><input type="text" class="oaui-input-text" value="0" title="排序" name="sort"  /></td>
	            </tr>
				</notempty>
	            <tr>
                	<td>&nbsp;<input type="hidden" name="submit" value="1" /></td>
	            	<td>
                    	<input type="submit"  class="oaui-button oaui-button-submit" value=" 提 交 " />
                        <hr class="oaui-space oaui-space-x20">
                        <input type="button" onclick="window.history.back()"  class="oaui-button oaui-button-common" value=" 返 回 " />                        
                    </td>
	            </tr>
	        </table>
		</div>
	</form>
</body>
</html>