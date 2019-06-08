<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录</title>
{:W('Css','global_login')}
{:W('jQuery')}
{:W('Dialog')}
<script type="text/javascript" src="__STATIC__/js/login.js"></script>
<!--[if lte IE 6]>
<script type="text/javascript">window.location="__APP__/Compat/lteie6";</script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="__STATIC__/css/control_loading.css">
<link rel="stylesheet" type="text/css" href="__STATIC__/css/bootstrap.min.css">

</head>

<body>
	<!-- <include file="Base:noscript" />  -->
<img src="__STATIC__/image/170904/bg.jpg" class="img-responsive" alt="Responsive image"  style="width: 100%;position: relative;">
	<div class="mean">
		<header>
			雪亮工程视频管理中心
		</header>
		<div class="bag">
		<!-- <img src="images/bag.jpg"> -->
		<img src="__STATIC__/image/170904/bag.jpg" class="img-responsive bag_img" alt="Responsive image">
		 <div class="loading">
		 	<form action="__URL__/post" method="post">
			<p>用户登录</p>
			<div style="width: 95%;height: 70px;position: relative;">
				<div class="text"><img src="__STATIC__/image/170904/tou.png" style="padding-top: 10px;"></div><input type="text" maxlength="32" value="" name="account" id="account" autocomplete="off" placeholder="用户名" class="input1">
			</div>
			<div style="width: 95%;height: 70px;position: relative;">
				<div class="pass"><img src="__STATIC__/image/170904/suo.png" style="padding-top: 10px;"></div><input type="password" maxlength="32" value="" name="password" autocomplete="off" placeholder="密码" class="input2">	
				</div>
				<button type="submit" class="loadbtn">登陆</button>
			</form>
		</div>
	</div>
		<div class="footer">
			<img src="__STATIC__/image/170904/1_19.jpg"><p>中央中置办</p>
		</div>
	</div>


<!-- 	<div id="login_background"><img src="__STATIC__/image/login_background.jpg" width="100%" height="100%"/></div>
	<div id="main">
    	<div class="cf-o">
       
	    	<div id="info_panel" class="f-l" >

	        </div>
	        <div id="login_panel" class="f-r">
	        	<div id="login_container">
                	<div id="login_title">登录</div>
                    <div id="login_form">
                      	<form action="__URL__/post" method="post">
	                    	<label class="text-label account"><span class="placeholder" onselectstart="return false;">登录帐号</span><input placeholder="登录帐号" type="text"  class="text"	 maxlength="32" value="" name="account" id="account" autocomplete="off" />	</label>
	                        <label class="text-label password"><span class="placeholder" onselectstart="return false;">登录密码</span><input placeholder="登录密码" type="password" class="text"  maxlength="32" value="" name="password" autocomplete="off"    />	</label>
	                        <label class="button-label"><input type="submit" value=" 登　录 " class="submit" /></label>
                        </form>
                    </div>
                </div>
	        </div>             

        </div>
    </div> -->
</body>
</html>
