<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改密码</title>
{:W('Css','global_widget')}
{:W('Skin','widget')}
{:W('jQuery')}
{:W('Dialog')}
<script type="text/javascript">
dialog.parent.button([{
			value:'修改',
			autofocus:true,
			callback:function(){
				
				var password = $("#password").val();
				var repassword = $("#repassword").val();
				var oldpassword = $("#oldpassword").val();
				
				
				if(oldpassword.length==0){
					dialog.tips('error','请输入原密码');
					$("#oldpassword").focus();
					return false;						
				}
				
				if(password.length==0){
					dialog.tips('error','新密码不能为空');
					$("#password").focus();
					return false;						
				}
				
				if(password.length>32 || password.length<6){
					dialog.tips('error','新密码长度为 6-32 位');
					$("#password").focus();
					return false;						
				}
				
				if(repassword.length==0){
					dialog.tips('error','确认密码不能为空');
					$("#repassword").focus();
					return false;						
				}
				
				if(password != repassword){
					dialog.tips('error','两次输入密码不一致');
					$("#repassword").val('');
					$("#repassword").focus();
					return false;
				}
			
				
				$.ajax({
					  url:'__URL__/password_post',
					  type:'POST',
					  data:{oldpassword:oldpassword,password:password,staff_id:<?php echo $staff_id;?>},
					  dataType:'json',
					  beforeSend:function(){
						dialog.loading.show();  
					},
					complete:function(){
						dialog.loading.hide();	
					},
					 success:function(response){
						if(response.status=='success'){
							
							parent.dialog.alert('密码修改成功<br />点击确定后系统将需要你重新登陆！',function(){
								window.top.location.href = '__APP__';
							});
							dialog.parent.close();
						}else{
							dialog.tips(response.status,response.info);
						}
					  },
					  error:function(){
					    dialog.tips('error','网络故障，请重试！');	  
					}
					
				})
				
				return false;
			}	
	},{value:'取消'}])
	


</script>
</head>

<body>
	<div class="oaui-iform">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
        	<tr>
            	<td width="30%" align="right"><strong>原密码</strong>：</td>
                <td><input type="password" autocomplete="off" class="oaui-input-text" value="" id="oldpassword" /></td>
            </tr>
        	<tr>
            	<td width="30%" align="right"><strong>新密码</strong>：</td>
                <td><input type="password" autocomplete="off" class="oaui-input-text" value="" id="password" /></td>
            </tr>
            <tr>
            	<td width="30%" align="right"><strong>确认密码</strong>：</td>
                <td><input type="password" autocomplete="off" class="oaui-input-text" value="" id="repassword" /></td>
            </tr>
        </table>
	</div>	
</body>
</html>