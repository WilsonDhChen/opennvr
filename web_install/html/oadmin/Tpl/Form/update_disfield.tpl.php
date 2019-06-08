<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$field_name}-【{$form.name}】</title>
<style type="text/css">
body{ font-family:'Microsoft Yahei';}
body,ul,ol,p,h1,h2,h3,h4,h5,h6{margin:0;padding:0;}
a{ text-decoration:none;}
.disfield-file-module{ padding:6px;min-width:400px;}
.formui-file-gridview li{ border-bottom:1px dotted #efefef; padding:3px; margin:3px 0;}
.formui-file-gridview .fileview{ display:inline-block; vertical-align:middle;width:60px; height:60px; overflow:hidden;}
.formui-file-gridview .fileview img{ background: url(__STATIC__/image/alphabg.png);}
.formui-file-gridview .fileview-doc{ background:#efefef; text-align:center; color:#ccc; line-height:26px;}
.formui-file-gridview .fileview-doc strong{ display:block;}
.formui-file-gridview .filename{ display:inline-block; vertical-align:middle;overflow:hidden;text-overflow:ellipsis;white-space:nowrap; width:300px; cursor:default; color:#666; font-size:14px;}
.formui-file-gridview .filedelete{float:right; margin-top:15px; display:inline-block;width:20px; height:20px; text-align:center; line-height:18px; font-size:20px; color:#fff;background: url(__STATIC__/image/delete.gif) no-repeat center center;}
.formui-file-none-tips{ text-align:center; padding-top:40px; color:#666;}
.formui-file-none-face{text-align:center;font-size:80px; padding-top:20px; color:#ccc}
</style>
{:W('jQuery')}
{:W('Dialog')}
</head>

<body>
<div class="formui-disfield">
<switch name="field_type">
<case value="file">
	<div class="disfield-file-module">
    	<empty name="field_val">
        	<p class="formui-file-none-tips">此条记录暂未上传任何文件</p>
            <p class="formui-file-none-face">¯\\\\\\\\_(ツ)_/¯</p>
        <else />
    	<?php $files = explode(',',$field_val);?>
    	<div class="disfield-inner">
        	<ul class="formui-file-gridview">
        	<volist name="files" id="file">
					<li>
                    	<?php $path_parts = pathinfo($file); ?>
                    	<if condition="in_array($path_parts['extension'],array('jpg','jpeg','png','gif','bmp','webp'))">
                    	<a class="fileview fileview-img" href="{$file}" target="_blank" title="点击查看原图"><img src="{$file}" width="60" height="60" /></a>
                        <else />
                        <a class="fileview fileview-doc" href="{$file}" target="_blank" title="点击下载文件"><strong>{$path_parts['extension']}</strong>文件</a>
                        </if>
                        <p class="filename" title="{$path_parts['basename']}">{$path_parts['basename']}</p>
                        <a class="filedelete" href="javascript:/*删除此文件*/;" title="删除此文件" onclick="uploadedFileDelete('<?php echo $file?>','<?php echo $field_val?>')"></a>
                    </li>
            </volist>
            </ul>
        </div>
        
		<script type="text/javascript">
			function uploadedFileDelete(file,files){
				var data = {record_id:'<?php echo $record_id?>',field_id:'<?php echo $field_id?>'};
				data.ajax = 'uploaded_file_delete';
				data.file = file;
				data.files = files;
				$.ajax({
					url:'__URL__/<?php echo ACTION_NAME?>',
					type:'POST',
					data:data,
					dataType:'json',
					beforeSend:function(){
						dialog.loading.show();
					},
					complete:function(){
						dialog.loading.hide();
					}				
				}).done(function(response){
					if(response.status=='success'){
						window.parent.uploadedFileDeleteCallback('<?php echo $field_id?>');
						dialog.tips('success',response.info,function(){
							window.location.reload();	
						});
					}else{
						dialog.alert(response.info)	
					}
				})			
				
			}
		</script>        
	</empty>
    </div>
</case>
</switch>
</div>    
</body>
</html>