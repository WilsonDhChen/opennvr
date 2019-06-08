<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录</title>
{:W('Css','global')}
<link rel="stylesheet" type="text/css" href="__STATIC__/tool/awesome/css/font-awesome.min.css">
<link type="text/css" rel="stylesheet" href="__STATIC__/css/login_new.css" />
{:W('jQuery')}
{:W('Dialog')}
<script type="text/javascript" src="__STATIC__/js/login.js"></script>
<!--[if lte IE 6]>
<script type="text/javascript">window.location="__APP__/Compat/lteie6";</script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="__STATIC__/css/control_loading.css">

</head>

<body>
<div class="login-page">
    <div class="login-box">
        <form action="__URL__/post" id="login_form" method="post">
        <h2 class="login-title">雪亮工程视频管理中心</h2>
        <div class="login-form">
            <div class="login-input">
                <i class="icon fa fa-user-circle fa-fw fa-lg"></i>
                <input type="text" class="login-input-text" id="account" name="account" autocomplete="off" placeholder="用户名" />
            </div>
            <div class="login-input">
                <i class="icon fa fa-lock fa-fw fa-lg"></i>
                <input type="password" class="login-input-text" name="password" autocomplete="off" placeholder="密码" />
            </div>
            <div class="login-input login-button">
				<button type="submit" class="loadbtn">登陆</button>
            </div>
        </div>
        </form>
    </div>
</div>

</body>
</html>
