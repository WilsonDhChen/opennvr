<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>员工密码修改</title>
{:W('Css','global_widget')}
{:W('Skin','widget')}
{:W('jQuery')}
{:W('Dialog')}
<script type="text/javascript">

dialog.parent.button([{
			value:'提交',
			autofocus:true,
			callback:function(){
				
				var password = $("#password").val();
				
				if(password.length==0){
					dialog.tips('error','新密码不能为空');
					return false;						
				}
			
				$.ajax({
					  url:'__URL__/password_post',
					  type:'POST',
					  data:{password:password,staff_id:<?php echo $staff_id;?>},
					  dataType:'json',
					  beforeSend:function(){
						dialog.loading.show();  
					},
					complete:function(){
						dialog.loading.hide();	
					},
					 success:function(response){
							dialog.tips(response.status,response.info,function(){
								if(response.status=='success'){
									dialog.parent.close().remove();
								}
							});
					  },
					  error:function(){
					    dialog.tips('error','网络故障，请重试！');	  
					}
					
				})
				
				return false;
			}	
	},{value:'关闭'}])


</script>
</head>

<body>

	<div class="oaui-iform">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
        	<tr>
            	<td width="30%" align="right"><strong>新密码</strong>：</td>
                <td><input type="text" autocomplete="off" class="oaui-input-text" value="123456" id="password" /></td>
            </tr>
        </table>
	</div>	
</body>
</html>