<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$cnname}-添加子级</title>
{:W('Css','global_widget')}
{:W('Skin','widget')}
<style type="text/css">
html,body{min-width:600px;}
.config-node-path li{float:left;}
.config-node-path li:after{content:'»'; color:#aaa; font-size:14px;}
.config-node-path li:last-child:after{content:''}
</style>
{:W('jQuery')}
{:W('Dialog')}
<script type="text/javascript" src="__STATIC__/js/jquery.witaform.js"></script>
<script type="text/javascript">
dialog.parent.button([{
			value:'添加',
			autofocus:true,
			callback:function(){
				$('#OAForm').submit();
				return false;				
			}	
	},{value:'取消'}])
	
$(function(){

	$('#OAForm').witaForm({
			  
		<?php 
			if(spower($idname.'__identifier') && !empty($identifier_name)){
				echo 'rule:{'.($config_rule?'"config_name":{'.$config_rule.'},':'').($identifier_rule?'"identifier_name":{'.$identifier_rule.'},':'').$rules.'"config_sort":{required:true,ptint:true}'."},\n";
			}else{
				echo 'rule:{'.($config_rule?'"config_name":{'.$config_rule.'},':'').$rules.'"config_sort":{required:true,ptint:true}'."},\n";	
			}
	    ?>		submit:function(){
			$(this).find(":submit").prop({disabled:true});
			parent.dialog.loading.show();
		},
		complete:function(){
			$(this).find(":submit").prop({disabled:false});
			parent.dialog.loading.hide();
		},		
		alert:function(tips){
			dialog.tips('error',tips);
		},
		error:function(){
			dialog.tips('error','网络故障，请重试');
		},
		success:function(response){
			
			dialog.tips(response.status,response.info,function(){
				if(response.status=='success'){
					window.parent.location.reload();		
				}				
			});				

		}
		
		
	});
	
})

</script>
</head>

<body>
	<div class="oaui-form">
    	<form action="__SELF__" id="OAForm" method="post">
        <input type="hidden" name="submit" value="1" />
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
        	<tr>
            	<td width="30%" align="right"><strong>父级路径</strong>：</td>
                <td>{$node_path}</td>
            </tr>
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
                <td align="right"><strong>{$field_name}</strong>：</td>
                <td><input type="text" class="oaui-input-text" value="" title="{$field_name}" name="{$field_key}"  /></td>
            </tr>
            </foreach>
			<notempty name="config_sort">
            <tr>
                <td width="20%" align="right"><strong>排序</strong>：</td>
                <td><input type="text" class="oaui-input-text" value="0" title="排序" name="config_sort"  /></td>
            </tr>
            </notempty>
        </table>
        </form>
	</div>	
</body>
</html>