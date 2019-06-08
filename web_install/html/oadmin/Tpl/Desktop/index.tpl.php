<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="initial-scale=0.5, maximum-scale=0.5, user-scalable=no">
<meta name="renderer" content="webkit">
<link rel="icon" href="__DIR__/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="__DIR__/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="__STATIC__/tool/awesome/css/font-awesome.min.css">
<title>lyVTS2.0</title>
{:W('Css','global_oaui')}
{:W('Skin','oaui')}
{:W('jQuery')}
{:W('Dialog')}
{:W('ContextMenu')}
<script type="text/javascript" src="__STATIC__/js/oaui.js"></script>
<script type="text/javascript">
$(function(){
	//{//桌面UI初始化}
	OAUI.Desktop.Init({AppPath:'__APP__',AppSkin:'__STATIC__/skin/<?php echo $staff['skin']?>'});
})
</script>
</head>

<body>
<div id="OA_Tooler">
	<div class="oaui-toper">
		<div class="oaui-logo">
	    	<img src="__STATIC__/image/logo.png" title="{$sys_cnname}" alt="{$sys_cnname}" width="160" height="38">
	    </div>
	    <div class="oaui-taskbar">
	    	<ul class="taskbar-wrap" id="OA_Taskbar"><li class="tasktab-item tasktab-item-active tasktab-desktop" id="taskbar_0" data-appid="0" onclick="OAUI.Taskbar.Switch('0')" title="应用桌面"><i class="tasktab-icon fa fa-home fa-fw"></i><span class="tasktab-text">应用桌面</span><span class="tasktab-close"></span></li></ul>
	    </div>
    </div>
</div>
<div id="OA_Mainer">
	<div class="oaui-pagetabs" id="OA_Pagetab">
	  	<div class="pagetab-item" id="pagetab_0">
			<div class="pagetab-sidebar">
		    	<div class="sidebar-wrap">
		        	<dl class="sidetab-item sidetab-item-navi sidetab-item-staff">
					  <dt>
                        <i class="site-icon fa fa-user fa-fw fa-lg"></i>
					  	<p class="site-name">{$staff.username}</p>
					  </dt>
<!--
                      <dd>
                      	<ul class="side-submenu">
                        	<li>【员工身份】</li>
							<volist name="staff_roles" id="role">
                            <li>{$role.role_name}</li>
                            </volist>
                        </ul>
                      </dd>
-->
		        	</dl>
                    <dl class="sidetab-item" onclick="OAUI.Desktop.Logout()">
					  <dt>
                        <i class="site-icon fa fa-sign-out fa-fw fa-lg"></i>
					  	<p class="site-name">退出登录</p>
					  </dt>
		        	</dl>
		        </div>
		    </div>
		    <div class="pagetab-desktop" id="OA_Desktop"></div>
	    </div>
    </div>
    <div class="oaui-loading" id="OA_Loading"><div></div></div>
</div>

</body>
</html>
