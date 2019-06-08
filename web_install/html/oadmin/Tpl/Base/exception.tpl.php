<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>系统发生错误</title>
<style type="text/css">
body{ background:#f0f0f0; font-family:'Microsoft Yahei';}
#container{max-width:1000px; margin:6% auto 0;}
.eface{ text-align:center; font-size:80px;color:#ccc;}
h1{ color:#f0f0f0; font-size:26px; padding:3px 6px 5px;background:#DE0536; border-radius:6px; box-sizing:border-box;word-wrap: break-word;}
fieldset{ border:1px dotted #ccc; border-radius:6px;}
legend{ color:#666;}
</style>
</head>

<body>
	<div id="container">
    	
    	<h1><?php echo strip_tags($e['message']);?></h1>
        <?php if(isset($e['file'])):?>
        <div class="exception-show exception-info">
        	<fieldset>
            	<legend>错误位置</legend>
                <p>FILE: <?php echo $e['file'] ;?></p>
                <p>LINE: <?php echo $e['line'] ;?></p>
             </fieldset>
        </div>
        <?php endif;?> 
        <div class="eface">¯\_(ツ)_/¯</div>
        <?php if(isset($e['trace'])):?>
        <div class="exception-show exception-trace">
			<div class="title">
				<h3>TRACE</h3>
			</div>
			<div class="text">
				<p><?php echo nl2br($e['trace']);?></p>
			</div>
            
        	<fieldset>
            	<legend>TRACE</legend>
                <p><?php echo nl2br($e['trace']);?></p>
             </fieldset>                    
        </div> 
         <?php endif;?>        
    </div>
</body>
</html>