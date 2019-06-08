<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$config.name}-生成栏目</title>
{:W('Css','global_widget')}
{:W('Skin','widget')}
<style type="text/css">
html,body{min-width:600px;}
.split-line{ border-bottom:1px dashed #f0f0f0;}
.form-item-group label{ margin-right:5px; background:#f0f0f0; padding:3px 6px; border-radius:3px; user-select:none;}
.form-item-group label:hover{ background:#e0e0e0;}
.oaui-select{width:390px;}
.oaui-input-text{ margin-bottom:3px; width:388px;}
.config-fields{ height:180px; overflow-x:hidden;overflow-y:auto;}
.config-fields li{ margin-bottom:1px;}
.config-fields .oaui-input-text{ margin:0 1px 0 0; width:100px;}
.config-fields .oaui-button{ min-width:102px; height:26px; line-height:26px; margin-top:3px;}
.config-fields label{ background:#d0d0d0; display:inline-block; vertical-align:middle; height:28px; line-height:28px; padding:0 5px; color:#666;}
.config-fields .remove-button{ background:#c00; display:inline-block; vertical-align:middle; width:28px; height:28px; overflow:hidden; font-size:22px; font-weight:bold; line-height:28px; margin-left:1px;color:#fff; border:none;}
.select-ui select{color: #333;border: 1px solid #d0d0d0; margin-right: 1px; height: 28px; line-height: 28px;}
.select-ui select:disabled {color: #ccc;border: 1px solid #ececec}
</style>
{:W('jQuery')}
{:W('Dialog')}
<script type="text/javascript" src="__STATIC__/js/jquery.witaform.js"></script>
<script type="text/javascript">
dialog.parent.button([{
			value:"<?php echo empty($navi)?'确认生成':'重新生成'?>",
			autofocus:true,
			callback:function(){
				$('#OAForm').submit();
				return false;
			}	
	},{value:'取消'}])
	
$(function(){

	$('#OAForm').witaForm({
		rule:{
			navi_name : 'required' <?php echo $rule?>
		},
		submit:function(){
			if($('#widgetSelect_1').val()===''){
				dialog.tips('error','所属栏目必须选择');
				return false;	
			}
			parent.dialog.loading.show();
		},
		complete:function(){
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
					dialog.parent.close();		
				}				
			});				

		}
		
		
	});
	
	//{//类型切换}
	$('select[name=config_type]').change(function(){
		window.location = '?type='+$(this).val();	
	});
	
	//
	$(':checkbox[name=show_identifier_name]').click(function(){
		if($(this).is(":checked")){
			$(this).next('div').find('input').prop('disabled',false);
		}else{
			$(this).next('div').find('input').prop('disabled',true);	
		}
	})
	
	//attrs 多文本辅助输入(使用 Ctrl+单击)
	$('.field-rule-area').on('click','.field-rule-code',function(event){
		if(event.ctrlKey && $(this).is(':enabled')){
			parent.dialog.input(this);		
		}
	})	
	
	//WaitForm rule maker
	$('.field-rule-area').on('dblclick','.field-rule-code',function(){
		$this = $(this);
		if($this.is(':enabled')){
			parent.dialog.preset(
				{
                    padding:0,
                    backdropOpacity:0.2,
					onclose:function(){
                        if(this.returnValue!==false) {
                            $this.val(this.returnValue)
                        }
					}
				}
			).ajax('__APP__/api/witaform','WaitForm Rule Maker',{type:$(this).data('rule-field'),rule:$(this).val()});
		}
	})
	
})

</script>
<if condition="in_array($type,array('sets','node'))">
<script>
$(function(){
	var configField_Tpl = $('#configFieldsItem').html();
	$('#configFieldsItem').remove();
	
	if($('#configFields').find('li').length==0){
		$('#configFields').append(configField_Tpl.replace(/\{%i%\}/g,'0'));	
	}
	
	$('#configFields_Insert').click(function(){
		var index = $('#configFields').find('li').length;
		$('#configFields').append(configField_Tpl.replace(/\{%i%\}/g,index));
	})
	
	$('#configFields').on('click','.remove-button',function(){
		$(this).parent('li').remove();
		//{//删除需要重置索引}
		$('#configFields').find('li').each(function(index, element) {
            var $field_enname = $(this).find(":text[name^='field_enname[']");
			$field_enname.prop('name','field_enname['+index+']');
			
			var $field_cnname = $(this).find(":text[name^='field_cnname[']");
			$field_cnname.prop('name','field_cnname['+index+']');
			
			var $field_rule = $(this).find(":text[name^='field_rule[']");
			$field_rule.prop('name','field_rule['+index+']');
			
			var $field_show = $(this).find(":checkbox[name^='field_show[']");
			$field_show.prop('name','field_show['+index+']');
			
        });
	}) 
})
</script>
</if>
<notempty name="navi">
<script type="text/javascript">
    $(function () {
       $(".select-ui").on('change', "select[name='navi_parent_id[]']", function () {
           if($(this).val()==''){
               $(this).hide();
           }
       });

    });
</script>
</notempty>
</head>

<body>
	<div class="oaui-form">
    	<form action="__SELF__" id="OAForm" method="post">
        <input type="hidden" name="submit" value="1" />
        <input type="hidden" name="config_type" value="{$type}" />
        <input type="hidden" name="tonavi_type" value="config" />
        <input type="hidden" name="navi_id" value="{$navi.navi_id}" />
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td align="right"><strong>数据类型</strong>：</td>
				<td>
					<select name="config_type" class="oaui-select">
						<foreach name="types" item="item">
							<option value="{$item}" <?php echo $type==$item?'selected':''?> >{$item}</option>
						</foreach>
					</select>
				</td>
			</tr>
            <tr>
                <td width="20%" align="right"><strong>所属栏目</strong>：</td>
                <td class="select-ui">{:W('Select',array('select_name'=>'navi_parent_id','table'=>'sys_navi','node_id'=>'navi_id','node_name'=>'navi_name','disabled'=> empty($navi)?false:true ,'selected'=>$parentsid))}</td>
            </tr>
            <tr>
                <td align="right"><strong>栏目名称</strong>：</td>
                <td><input type="text" class="oaui-input-text" value="{$navi.navi_name|default=$config['name']}" title="栏目名称" name="navi_name" placeholder="请输入要生成的栏目名称"  /></td>
            </tr>
            <tr class="split-line navi-action">
                <td align="right"><strong>栏目权限</strong>：</td>
                <td class="form-item-group">
                	<label title="列表权限必须选择"><input type="checkbox" disabled="disabled" checked="checked"><input type="hidden" name="navi_action[]" value="index">列表</label>
                    <label><input type="checkbox" name="navi_action[]" <?php echo (empty($navi) || in_array('insert',$navi_actions))?'checked="checked"':'' ?> value="insert">添加</label>
                    <label><input type="checkbox" name="navi_action[]" <?php echo (empty($navi) || in_array('update',$navi_actions))?'checked="checked"':'' ?> value="update">修改</label>
                    <label><input type="checkbox" name="navi_action[]" <?php echo (empty($navi) || in_array('delete',$navi_actions))?'checked="checked"':'' ?> value="delete">删除</label>
                    <label><input type="checkbox" name="navi_action[]" <?php echo (empty($navi) || in_array('detail',$navi_actions))?'checked="checked"':'' ?> value="detail">详情</label>
                    <label><input type="checkbox" name="navi_action[]" <?php echo (empty($navi) || in_array('identifier',$navi_actions))?'checked="checked"':'' ?> value="identifier">标识</label>
                </td>
            </tr>                        
            <tr>
                <td width="20%" align="right"><strong>配置名称</strong>：</td>
                <td>{$config.name}</td>
            </tr> 
            <tr>
                <td align="right"><strong>配置标识</strong>：</td>
                <td>{$config.identifier}</td>
            </tr>         
            <tr>
                <td align="right" valign="top"><strong>记录名称</strong>：</td>
                <td class="field-rule-area">
                	<input type="text" class="oaui-input-text" value="{$attrs.config_name}" title="记录名称" name="config_name" placeholder="记录主名称:config_name"  />
                 	<input type="text" class="oaui-input-text field-rule-code" data-rule-field="text" value="{$attrs.config_rule|quotes_entities}" title="记录名称规则" placeholder="记录名称验证规则:config_rule" name="config_rule"  />
                 </td>
            </tr>
            <tr>
                <td align="right" valign="top"><strong>显示标识</strong>：</td>
                <td>
                	<input type="checkbox" name="show_identifier_name" style="margin-bottom:3px;" <?php echo $attrs['identifier_name']?'checked="checked"':'' ?> value="1">
                	<div class="field-rule-area">
                		<input type="text" class="oaui-input-text" <?php echo $attrs['identifier_name']?'':'disabled="disabled"' ?> value="{$attrs.identifier_name}" title="标识名称" name="identifier_name" placeholder="标识名称:identifier_name"  />
                 		<input type="text" class="oaui-input-text field-rule-code" data-rule-field="text" <?php echo $attrs['identifier_name']?'':'disabled="disabled"' ?> value="{$attrs.identifier_rule|quotes_entities}" title="标识名称规则" placeholder="标识名称验证规则:identifier_rule" name="identifier_rule"  />
                    </div>
                 </td>
            </tr>
            <tr>
                <td align="right" valign="top"><strong>字段显示</strong>：</td>
                <td class="form-item-group">
                    <eq name="type" value="sets">
                        <label><input type="checkbox" name="show_config_id" <?php echo $attrs['config_id']?'checked="checked"':'' ?> value="1">记录ID</label>
                        <label><input type="checkbox" name="show_config_time" <?php echo $attrs['config_time']?'checked="checked"':'' ?> value="1">添加时间</label>
                    </eq>
                    <label><input type="checkbox" name="show_config_sort" <?php echo $attrs['config_sort']?'checked="checked"':'' ?> value="1">排序字段</label>
                </td>
            </tr>
        <eq name="type" value="node">
            <tr>
                <td align="right"><strong>节点层级</strong>：</td>
                <td><input type="text" class="oaui-input-text" value="{$attrs.config_level}" title="节点层级" name="config_level"  placeholder="最大可允许的节点层级:config_level"  /></td>
            </tr>
        </eq>
        <if condition="in_array($type,array('sets','node'))">
            <tr>
                <td align="right" valign="top"><strong>自定义字段</strong>：</td>
                <td>
                	<div class="config-fields scrollbar">
                    	<template id="configFieldsItem">
                        <li><input type="text" class="oaui-input-text" placeholder="字段标识" value="" name="field_enname[{%i%}]"><input type="text" class="oaui-input-text" placeholder="字段名称" value="" name="field_cnname[{%i%}]"><input type="text" class="oaui-input-text field-rule-code" data-rule-field="text" placeholder="字段验证规则" value="" name="field_rule[{%i%}]"><label><input type="checkbox" name="field_show[{%i%}]" value="1"><span>列表显示</span></label><input type="button" value="-" title="移除" class="remove-button"></li>
                        </template>                    
                        <ul id="configFields" class="field-rule-area">
                        	<?php $item_index = -1; ?>
                        	<foreach name="field_name" item="cnname" key="enname">
                            <?php 
								$item_index++;
								$field_key = substr($enname,6);
								$field_rule_key = 'rule_'.$field_key;
								$field_rule_val = isset($field_rule[$field_rule_key])?$field_rule[$field_rule_key]:'';
							?>
                            <li><input type="text" class="oaui-input-text" placeholder="字段标识" value="{$field_key}" name="field_enname[{$item_index}]"><input type="text" class="oaui-input-text" placeholder="字段名称" value="{$cnname}" name="field_cnname[{$item_index}]"><input type="text" class="oaui-input-text field-rule-code" data-rule-field="text"  placeholder="字段验证规则" value="{$field_rule_val|quotes_entities}"  name="field_rule[{$item_index}]"><label><input type="checkbox" name="field_show[{$item_index}]" <?php echo in_array($field_key,$config_fields)?'checked="checked"':'' ?> value="1"><span>列表显示</span></label><input type="button" value="-" title="移除" class="remove-button"></li>
                            </foreach>
                        </ul>
                        <input type="button" id="configFields_Insert" class="oaui-button oaui-button-common" value="+ 增加">
                    </div>
                </td>
            </tr>        	
        </if>
        </table>
        </form>
	</div>	
</body>
</html>