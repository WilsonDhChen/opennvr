<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$cnname}-记录详情</title>
{:W('Css','global_widget')}
{:W('Skin','widget')}
{:W('jQuery')}
</head>

<body>
        <div class="oaui-form">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
                <if condition="spower($idname.'__identifier') && !empty($identifier_name)">
	        	<tr>
	            	<td width="20%" align="right"><strong>{$identifier_name}</strong>：</td>
	                <td>{$detail.identifier}</td>
	            </tr>                 
                </if>
	        	<tr>
	            	<td width="20%" align="right"><strong>{$config_name|default='记录名称'}</strong>：</td>
	                <td>{$detail.name}</td>
	            </tr> 
            	<foreach name="detail['attrs']" key="field_key" item="field_val">
	        	<tr>
	            	<td width="20%" align="right"><strong><?php echo $field_key=='value'? (empty($config_name)?'记录名称':$config_name).'值' :$field_key; ?></strong>：</td>
	                <td>{$field_val}</td>
	            </tr>
                </foreach>
	        	<tr>
	            	<td width="20%" align="right"><strong>添加时间</strong>：</td>
	                <td>{$detail.insert_time|date='Y-m-d H:i:s',###}</td>
	            </tr>
	        	<tr>
	            	<td width="20%" align="right"><strong>排序</strong>：</td>
	                <td>{$detail.sort}</td>
	            </tr>
	            <tr>
                	<td>&nbsp;</td>
	            	<td><input type="button" onclick="window.history.back()"  class="oaui-button oaui-button-common" value=" 返 回 " /></td>
	            </tr>
	        </table>
		</div>
</body>
</html>