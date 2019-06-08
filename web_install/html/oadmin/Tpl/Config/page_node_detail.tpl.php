<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$cnname}-节点详情</title>
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
<script type="text/javascript">
dialog.parent.button([{value:'关闭',autofocus:true}])
</script>
</head>

<body>
	<div class="oaui-form">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
        	<tr>
            	<td width="30%" align="right"><strong>父级路径</strong>：</td>
                <td>{$node_path}</td>
            </tr>
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
            <foreach name="fields" item="field_name" key="field_key">
            <tr>
                <td align="right"><strong>{$field_name}</strong>：</td>
                <td><?php echo $detail['attrs'][substr($field_key,6)] ?></td>
            </tr>
            </foreach>
            <tr>
                <td width="20%" align="right"><strong>添加时间</strong>：</td>
                <td>{$detail.insert_time|date='Y-m-d H:i:s',###}</td>
            </tr>            
            <tr>
                <td align="right"><strong>排序</strong>：</td>
                <td>{$detail.sort}</td>
            </tr>             
        </table>
	</div>	
</body>
</html>