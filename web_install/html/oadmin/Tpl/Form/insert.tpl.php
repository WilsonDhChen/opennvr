<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加-{$form.name}</title>
{:W('Css','global_widget')}
<style type="text/css">
.formui-module{ padding:10px 10px 100px 10px; font-family:'Microsoft Yahei';font-size:14px;}
.formui-list{ border-collapse:collapse;}
.formui-list .field-label{ padding:6px; text-align:right; color:#666;min-width:140px;}
.formui-list .field-input{ padding:6px;}
.formui-list .button-row td{ padding-top:20px;}

/* 表单field 样式 */
/*input:text*/
.formui-text{width:260px; height:26px; line-height:26px;font-family:'Microsoft Yahei'; color:#333; text-indent:6px;border-color: #d0d0d0;border-style: solid;border-width: 1px;box-sizing:border-box;}
.formui-text:focus{border-color: #AAAAAA #CCCCCC #CCCCCC #AAAAAA;}
/*input:textarea*/
.formui-textarea{min-height:100px; min-width:380px; padding:3px; resize:none;border-color: #d0d0d0;border-style: solid;border-width: 1px;box-sizing:border-box;}
.formui-textarea:focus{border-color: #AAAAAA #CCCCCC #CCCCCC #AAAAAA;}
/*input:file*/
.formui-file{width:260px;height:28px; position:relative;}
.formui-file dl{width:260px;min-height:26px;border-color:#d0d0d0;border-style:solid;border-width:1px; background:#fff; position:absolute; top:0; left:0;}
.formui-file dl.focus{border-color: #AAAAAA #CCCCCC #CCCCCC #AAAAAA;}
.formui-file dt:before{ font-size:26px; font-weight:bold; content:'+'; position:absolute;top:-3px; left:3px;}
.formui-file dt{ padding-left:26px; line-height:26px;color:#666; position:relative;}
.formui-file dt:after{ font-size:18px; color:#999; content:'\2228'; position:absolute;top:-2px; right:6px; display:none;}
.formui-file dl.uploaded dt:after{ display:block;}
.formui-file .fieldui-file-selecttips{ color:#aaa; padding-left:2px;}
.formui-file .fieldui-file-trigger{position:absolute;left:0; top:0;width:260px; height:26px; overflow:hidden; text-align:right;}
.formui-file .fieldui-file-input{ font-size:400px; opacity:0;filter:alpha(opacity=0);width:260px; height:26px; overflow:hidden; cursor:pointer; *position:absolute;*left:0;*top:0;}
.formui-file dd{background:#f9f9f9; margin:1px; display:none;}
.formui-file dd:hover{background:#f6f6f6;}
.formui-file dd ul{ overflow:hidden;_zoom:1;}
.formui-file dd li{float:left; display:block;height:38px;}
.formui-file .fieldui-fileview-cover{width:38px;}
.formui-file .fieldui-fileview-nocover{ font-size:12px; background:#ddd; text-align:center; color:#666;}
.formui-file .fieldui-fileview-name{width:198px;height:38px;line-height:38px; cursor:default; text-indent:3px; color:#333;overflow: hidden; text-overflow:ellipsis;white-space:nowrap; font-size:12px;}
.formui-file .fieldui-fileview-remove{width:20px;float:right;}
.formui-file .fieldui-fileview-remove-button{ display:block; margin-top:9px;width:20px; height:20px; font-weight:bold; cursor:pointer; overflow:hidden; font-size:20px; text-align:center; border-radius:50%; line-height:20px; color:#FF5559; font-family:Arial, Helvetica, sans-serif;}
.formui-file .fieldui-fileview-remove-button:hover{ color:#f00;}
/*input:radio*/
.formui-radio{ margin-right:6px;}
/*input:checkbox*/
.formui-checkbox{ margin-right:6px;}
/*input:range*/
.formui-range{width:260px;}
/*input:submit*/
.formui-submit,.formui-button{ padding:6px 22px; margin-left:10px;}
.formui-goback{ margin-left:80px;}
/*input:datepicker*/
.formui-datepicker{ height:26px !important; line-height:26px !important; border:1px solid #d0d0d0 !important; background-position:98% center !important;} 
.formui-datepicker:hover{border-color: #AAAAAA #CCCCCC #CCCCCC #AAAAAA !important;}

/* html5 date、datetime样式 */
::-webkit-inner-spin-button{ height:26px;}
::-webkit-calendar-picker-indicator{ margin-left:1px;padding:8px 4px;}

</style>
{:W('jQuery')}
{:W('Dialog')}
<script src="__STATIC__/js/form.js"></script>
<notempty name="has_datepicker">
{:W('Calendar')}
</notempty>
<script type="text/javascript">

$(function(){
	
	$('#form').witaForm({
				<?php if(!empty($rules)){ echo 'rule:{'.$rules."},\n"; }?>
	  			submit:function(){
					dialog.loading.show();
				},
	  			complete:function(){
					dialog.loading.hide();
				},
				success:function(response){
					dialog.alert(response.info,function(){
						if(response.status=='success'){
							window.location.reload();	
						}						
					});

				},
				error:function(response){
					dialog.tips('error','网络故障，请重试!');
				},				
				alert:function(msg){
					dialog.tips('error',msg);
					
				}					
	});
	
	//input required 自定义提示
	$(':input').on('invalid',function(){
		dialog.tips('error',$(this).attr('title')+'不能为空或是不合法！');
		return false;
	})	
	
})
</script>
<notempty name="has_file">
<script type="text/javascript">

$(function(){
	//hover
	$('.formui-file').hover(function(){
		var $dl = $(this).find('dl');
		$dl.addClass('focus');
		$dl.find('dd').show();
		
	},function(){
		var $dl = $(this).find('dl');
		$dl.removeClass('focus');
		$dl.find('dd').hide();
		
	})
	
	
	//{//检测浏览器是否支持多文件上传/现代浏览器支持HTML5}
	var isHTML5 = 'multiple' in document.createElement('input') && typeof(FileReader)==='function';
	
	/* */
	var uploadFiles = [];
	var uploadIndex = 0;
	
	$('.formui-file').on('change','input:file',function(){
		
		var $dl = $(this).parent('.fieldui-file-trigger').prev('dl');
		var $file,$dd;
		
		//{//现代支持HTML5浏览器处理}
		if(isHTML5){
		
			//{//多文件处理}
			if($(this).attr('multiple')){
				var i = 0;
  				var eachFiles = function(files){
						if(i==files.length){
							return ;	
						}
						var file = files[i];
						$.when(fileHandlerH5(file,uploadIndex)).done(function($file){
							uploadFiles[uploadIndex] = file;
							uploadIndex++;
							//
							$dd = $('<dd></dd>');
							$dd.append($file);
							$dl.append($dd);
							//{//更新显示}
							$dl.addClass('uploaded');
							$dl.find('dt .fieldui-file-numtips').html('已选'+($dl.find('dd').length)+'个文件');
							i++;
							eachFiles(files);
						})						
					};
				
				eachFiles(this.files);			
				
			}else{
			//{//单文件处理}
			
				var file = this.files[0];
				$.when(fileHandlerH5(file,uploadIndex)).done(function($file){
					uploadFiles[uploadIndex] = file;
					//
					$dd = $('<dd></dd>');
					$dd.append($file);
					$dl.find('dd').remove();
					$dl.append($dd);
					//{//更新显示}
					$dl.addClass('uploaded');
					$dl.find('dt .fieldui-file-numtips').html('已选1个文件');
				})			
				
			}
			
			$(this).removeClass('notSelected').addClass('hasSelected');
			$(this).data('files',uploadFiles);	
			
			
		}else{
		//{不支持HTML5浏览器处理 lte ie9}	
			$(this).prop('id','field_file_id_'+uploadIndex);
			uploadIndex++;
			$file = fileHandlerIE(this);
			$dd = $('<dd></dd>');
			$dd.append($file);
			//{//多文件处理}
			if($(this).attr('multiple')){
				var $clone = $(this).clone().prop('id','');
				$(this).removeClass('notSelected').addClass('hasSelected');
				$(this).hide();
				$dl.next('.fieldui-file-trigger').append($clone);	
			}else{
				$(this).removeClass('notSelected').addClass('hasSelected');
				$dl.find('dd').remove();
			}
			
			$dl.append($dd);
			
			//{//更新显示}
			$dl.addClass('uploaded');
			$dl.find('dt .fieldui-file-numtips').html('已选'+($dl.find('dd').length)+'个文件');				
		}

	});
	
	
})

function fileHandlerIE(file){

	var filename = fileName(file),filesrc;
	var fileext  = filename.split('.').pop();
	var isimg = $.inArray(fileext,['jpg','jpeg','png','gif','bmp','webp']) > -1;
	var fileid = $(file).prop('id');
	var $ul = $('<ul><li class="fieldui-fileview-cover"></li><li class="fieldui-fileview-name"></li><li class="fieldui-fileview-remove"><span class="fieldui-fileview-remove-button" title="移除文件" onclick="removeFileIE(this,'+"'"+fileid+"'"+')">×</span></li></ul>');
	
	if(isimg){
		try{
			file.select();
			//{//IE预览权限问题处理}
			window.top.focus();	
			//{//获取本地文件路径}
			filesrc = document.selection.createRange().text;
			$ul.find('.fieldui-fileview-cover').css('filter',"progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='scale',src='" + filesrc + "')");
		}catch(e){
			$ul.find('.fieldui-fileview-cover').addClass('fieldui-fileview-nocover').html((fileext.length>4 || fileext.length==0 ?'未知':fileext)+'<br />文件');
		}		
		
	}else{
		$ul.find('.fieldui-fileview-cover').addClass('fieldui-fileview-nocover').html((fileext.length>4 || fileext.length==0 ?'未知':fileext)+'<br />文件');	
	}
	
	$ul.find('.fieldui-fileview-name').attr('title',filename).html(filename);
	return $ul;
}


function removeFileIE(o,id){
	
	var $dd = $(o).parent('li').parent('ul').parent('dd');
	var $dl = $dd.parent('dl');
	var $input = $('#'+id);
	//{//单个文件上传时，移除移除文件必须再复制一份}
	if(!$input.attr('multiple')){
		$dl.next('.fieldui-file-trigger').append($input.clone().removeClass('hasSelected').addClass('notSelected'));
	}
	//{//移除文件域}
	$input.remove();
	//{//删除文件展示列表中的对应记录}
	$dd.remove();
	
	//{//更新显示}
	if($dl.find('dd').length==0){
		$dl.removeClass('uploaded');
		$dl.find('dt .fieldui-file-numtips').html('选择文件');	
	}else{
		$dl.find('dt .fieldui-file-numtips').html('已选'+($dl.find('dd').length)+'个文件');	
	}
	

}

function removeFileH5(o,index){
	var $dd = $(o).parent('li').parent('ul').parent('dd');
	var $dl = $dd.parent('dl');
	//{//找到当前所属input:file}
	var $input = $dl.next('.fieldui-file-trigger').find(':file.fieldui-file-input');
	//{//获取 file data 数据存储的 已经选择文件}
	var files = $input.data('files');
	//{//移除当前要删除的文件}
	delete files[index];
	//重新赋值给 file data;
	$input.data('files',files);
	//{//删除文件展示列表中的对应记录}
	$dd.remove();	
	//{//更新显示}
	var filesNum = 0;
	$.map(files,function(n){
		if(typeof(n)==='object'){
			filesNum++;
		}
	});
	if(filesNum==0){
		$dl.removeClass('uploaded');
		$dl.find('dt .fieldui-file-numtips').html('选择文件');				
	}else{
		$dl.find('dt .fieldui-file-numtips').html('已选'+($dl.find('dd').length)+'个文件');	
	}		


}


function fileHandlerH5(file,index){
	
	var def = $.Deferred();
	var $ul = $('<ul><li class="fieldui-fileview-cover"></li><li class="fieldui-fileview-name"></li><li class="fieldui-fileview-remove"><span class="fieldui-fileview-remove-button" title="移除文件" onclick="removeFileH5(this,'+index+')">×</span></li></ul>');
	//{//图片文件读取url 作为显示预览}
	if(/^image\\\/\\\w+$/i.test(file.type)){
		var fileReader = new FileReader();
		fileReader.readAsDataURL(file);
		fileReader.onload = function(event){
			$ul.find('.fieldui-fileview-cover').css({'background-image':'url('+event.target.result+')','background-repeat':'no-repeat','background-size':'100% 100%'});
			$ul.find('.fieldui-fileview-name').attr('title',file.name).html(file.name);
			def.resolve($ul);	
		}			
	}else{
		var fileext  = file.name.split('.').pop();
		$ul.find('.fieldui-fileview-cover').addClass('fieldui-fileview-nocover').html((fileext.length>4 || fileext.length==0 ?'未知':fileext)+'<br />文件');
		$ul.find('.fieldui-fileview-name').attr('title',file.name).html(file.name);
		def.resolve($ul);
	}
	
	return def.promise();
}

function fileName(fileInput){
	//{//取得文件路径信息 (非真实路径 文件名真实)}
	var filePath = fileInput.value;
	var fileName ;
	//{//window系统下的 路径分隔符 \}
	if(filePath.indexOf('\\\\')>-1){
		var pathInfo = filePath.split('\\\\');
		fileName =  pathInfo[pathInfo.length-1];
	}else if(filePath.indexOf('/')>-1){
	//{//linux系统下的 路径分隔符 /}
		var pathInfo = filePath.split('/');
		fileName =  pathInfo[pathInfo.length-1];					
	}else{
	//{//无路径}
		fileName = filePath;
	}
	return fileName;
};

</script>
</notempty>

<notempty name="has_select">
<script type="text/javascript">

$(function(){
	//{//页面加载后所有select 触发一次 change事件}
	$('.formui-select').trigger('change');	
})

function FormSelectEventChange(select,config_id,from){
	var $select = $(select);
	//如果selectholder 选项option 移除所有子项select
	if($select.val()=='#selectholder#'){
		$select.nextAll('select').remove();
		return true;
	}
	//{//如果是multiple多选 或是没有子集  直接跳出}
	if($select.prop('multiple') || $select.data('unrelated') ){
		return true;
	}
		
	
	$.ajax({
		type:'POST',
		data:{ajax:'select',config_id:config_id,field_value:$select.val(),from : from || ''}
	}).done(function(response){
		if(response.status=='unrelated'){
			$select.data('unrelated',true);
			return ;
		}
		
		if(response.status=='none'){
			$select.nextAll('select').remove();
			return ;	
		}
		
		if(response.status=='related'){
			var field_name = $select.prop('name');
			if(field_name.indexOf('[]')===-1){
				field_name+='[]';
				$select.prop('name',field_name);
			}
			var $sub = $(response.data);
			var sub_id = $sub.prop('id');
			$sub.prop('name',field_name);
			$select.nextAll('select').remove();	
			
			$select.after($sub);
			//当前 select追加到dom后 立刻触发一次 change事件
			$('#'+sub_id).trigger('change');
			return ;
		}
	})
	
}
</script>
</notempty>
</head>

<body>

	<div class="formui-module">
    	<form id="form" method="post" enctype="{:$has_file ? 'multipart/form-data' : 'application/x-www-form-urlencoded'}">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="formui-list">
        	<volist name="fields" id="field">
        	<tr class="field-row">
            	<td class="field-label">{$field.label}：</td>
                <td class="field-input">{$field.input}</td>
            </tr>
            </volist>
        	<tr class="button-row">
                <td>&nbsp;</td>
                <td>
                	<input type="hidden" name="submit" value="1" />
                    <input type="submit" class="formui-submit" value="+添加"></button>
                	<input type="button" class="formui-button formui-goback" value="&laquo;返回" onclick="window.location='__URL__/{$form.sign}_index'"></button>
                </td>
            </tr>            
            
        </table>
        </form>
	</div>	
</body>
</html>