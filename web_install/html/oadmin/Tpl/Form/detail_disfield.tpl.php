<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$field_name}-【{$form.name}】</title>
<style type="text/css">
body{ background:#eee;}
.formui-disfield{ margin:40px;}
.formui-inner{ padding:10px;background:#fff; width:1000px; margin:0 auto; border-radius:6px; box-shadow:0 0 2px 1px rgba(0,0,0,0.2); min-height:600px;}
.formui-title{ margin-bottom:20px; padding:6px 0; border-bottom:1px dashed #efefef; text-align:center; font-size:18px;}
.formui-content{ padding:5px;}
.formui-content img{ max-width:800px;}
</style>
</head>

<body>

	<div class="formui-disfield">
		<div class="formui-inner">
        	<h1 class="formui-title">{$field_name}【{$form.name}】</h1>
        	<div class="formui-content">{$field_val}</div>
        </div>
	</div>	
</body>
</html>