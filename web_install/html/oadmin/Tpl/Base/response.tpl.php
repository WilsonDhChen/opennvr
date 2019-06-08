<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php if($jumpUrl!='javascript:history.back(-1);' ):?>
<meta http-equiv="refresh" content="<?php echo($waitSecond); ?>;URL=<?php echo $jumpUrl;?>" />
<?php endif;?>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>系统提示</title>
{:W('Css','global')}
{:W('jQuery')}
<style type="text/css">
body{ background:#f0f0f0; }
#container{ text-align:center;display:none;}
p.title{ vertical-align:middle; color:#555;text-align:center; font-size:100%;}
p.title img{ margin-right:5px; vertical-align:middle;}
p.button{ line-height:24px; margin-top:2%;}
</style>
<script type="text/javascript">
$(function(){
	$(window).on('resize',function(){
		page_resize();
	})
	
	function page_resize(){
		var modulus = Math.floor($(window).width()/200);
		var mgtop = ($(window).height()-parseInt($('#container').height()))/2.5;
		var font_size = modulus*6;
		if(font_size>22){ font_size = 22; }else if(font_size<12){ font_size=12; }
		$('#container').css({"font-size":font_size,"margin-top":mgtop})
		
		var img_width = modulus*8;
		if(img_width>32){ img_width=32; }else if(img_width<16){ img_width=16; }
			
		
		$('#status_icon').width(img_width);
	}
	
	page_resize();
	$('#container').show();
	
})
</script>
</head>
<?php

$tips = array();
if(isset($message)){
	$tips['status'] = 'success';
	$tips['info'] 	= $message;	
}else{
	$tips['status'] = 'error';
	$tips['info'] 	= $error;		
}

?>
<body>
	<div id="container">
    	<p class="title"><img src="__STATIC__/image/<?php echo($tips['status']);?>.png" class="va-m" id="status_icon"/><?php echo($tips['info']);?></p>
        <?php if(!empty($_SERVER['HTTP_REFERER']) && $jumpUrl!='javascript:history.back(-1);' ):?>
        <p class="button"><a href="<?php echo($jumpUrl); ?>" class="link"><img src="__STATIC__/image/tips_back.png" alt="返回" width="75" height="23" /></a></p>
        <?php endif;?>
    </div>
</body>
</html>