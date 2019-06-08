<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$cnname}-添加记录</title>
{:W('Css','global_widget')}
{:W('Skin','widget')}
{:W('jQuery')}
{:W('Dialog')}
<script type="text/javascript" src="__STATIC__/js/jquery.witaform.js"></script>
<script type="text/javascript">
$(function(){
	$('#OAForm').witaForm({
			  
		<?php 
			if(spower($idname.'__identifier') && !empty($identifier_name)){
				echo 'rule:{'.($config_rule?'"config_name":{'.$config_rule.'},':'').($identifier_rule?'"identifier_name":{'.$identifier_rule.'},':'').$rules.'"config_sort":{required:true,ptint:true}'."},\n";
			}else{
				echo 'rule:{'.($config_rule?'"config_name":{'.$config_rule.'},':'').$rules.'"config_sort":{required:true,ptint:true}'."},\n";	
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
            	<foreach name="fields" item="field_name" key="field_key">
	        	<tr>
	            	<td width="20%" align="right"><strong>{$field_name}</strong>：</td>
	                <td><input type="text" class="oaui-input-text" value="" title="{$field_name}" name="{$field_key}"  /></td>
	            </tr>
                </foreach>
				<notempty name="config_sort">
	        	<tr>
	            	<td width="20%" align="right"><strong>排序</strong>：</td>
	                <td><input type="text" class="oaui-input-text" value="0" title="排序" name="config_sort"  /></td>
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