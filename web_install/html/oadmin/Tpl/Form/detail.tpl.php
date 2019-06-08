<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>详情-{$form.name}</title>
{:W('Css','global_widget')}
<style type="text/css">
.formui-detail{ padding:20px 10px 10px; font-size:14px;}
.detail-data td{padding:8px 6px; border-bottom:1px dashed #f6f6f6}
.detail-data .detail-label{ text-align:right;min-width:100px;}
.detail-data .detail-input{ min-width:300px;}
.detail-data .button-row td{ padding-top:15px;border-bottom:none;}
.formui-detail-link{ padding:4px 8px; color:#fff; background:#82cff6; border-radius:3px;}
.formui-detail-link:hover{ background:#90d6fb;}
.formui-submit,.formui-button{ padding:6px 22px; margin-left:10px;}
.formui-files-gridview{ overflow:hidden;_zoom:1;max-width:300px;}
.formui-files-gridview li{float:left; display:inline-block; margin:2px;}
.formui-files-gridview .file-item-view{ display:inline-block;width:60px; height:50px;}
.formui-files-gridview .file-doc-view{ background:#efefef; text-align:center; color:#999; line-height:20px;}
.formui-files-gridview .file-doc-view strong{ display:block; padding-top:5px;}

</style>
{:W('jQuery')}
{:W('Dialog')}
</head>

<body>

	<div class="formui-detail">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="detail-data">
        	<volist name="details" id="detail">
        	<tr class="detail-row">
            	<td class="detail-label">{$detail.label}：</td>
                <td class="detail-input"><div title="{$detail.title}">{$detail.input}</div></td>
            </tr>
            </volist>
        	<tr class="button-row">
                <td>&nbsp;</td>
                <td>
                	<input type="button" class="formui-button formui-goback" value="&laquo;返回" onclick="window.location='__URL__/{$form.sign}_index'"></button>
                </td>
            </tr>            
        </table>
	</div>	
</body>
</html>