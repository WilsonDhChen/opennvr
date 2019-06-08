<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>个人资料</title>
{:W('Css','global_widget')}
{:W('Skin','widget')}
<style type="text/css">
.func-key {display: inline-block; overflow: hidden; width: 16px;height: 16px;margin: 0 1px;text-indent: -999em;background-position: center center;background-repeat: no-repeat;background-image: url(__STATIC__/image/icon_key.png);}
</style>
{:W('jQuery')}
{:W('Dialog')}
{:W('Calendar')}
<script type="text/javascript">


$(function(){
	
	$('#submit').click(function(){
		
						
				$.ajax({
					  url:'__URL__/edit_post',
					  type:'POST',
					  data:$('#form').serialize(),
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
									window.location.reload();
								}
							});
					  },
					  error:function(){
					    dialog.tips('error','网络故障，请重试！');	  
					}
					
				})			
		
	})	
	
})



</script>
</head>

<body>
   	<form id="form">
        <div class="oaui-form">
	        <input type="hidden" name="staff_id" value="{$staff_id}" />
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
	        	<tr>
	            	<td width="20%" align="right"><strong>员工工号</strong>：</td>
	                <td><strong>{$rs.staff_id}</strong></td>
	            </tr>        
	        	<tr>
	            	<td width="20%" align="right"><strong>员工帐号</strong>：</td>
	                <td>{$rs.username}</td>
	            </tr>
	            <tr>
	            	<td width="20%" align="right"><strong>入职日期</strong>：</td>
	                <td>{$rs.entry_date}</td>
	            </tr>            
	            <tr>
	            	<td width="20%" align="right"><strong>真实姓名</strong>：</td>
	                <td><input type="text" class="oaui-input-text" value="{$rs.realname}" name="realname"  /></td>
	            </tr>
	            <if condition="power('password')">
	            <tr>
	            	<td width="20%" align="right"><strong>密码</strong>：</td>
	                <td>******	<a href="javascript:;" title="修改密码" onclick="dialog.frame('__APP__/my/password/staff_id/{$staff_id}','修改密码')"  class="func-key">密码</a></td>
	            </tr>
	            </if>
	            <tr>
	            	<td width="20%" align="right"><strong>性别</strong>：</td>
	                <td>
	                    <input type="radio" name="gender" value="Male" id="gender_male" <?php echo $rs['gender']=='Male'?'checked':'' ?> /><label for="gender_male">男性</label> 　
	                    <input type="radio" name="gender" value="Female" id="gender_female" <?php echo $rs['gender']=='Female'?'checked':'' ?>  /><label for="gender_female">女性</label>　
	
	                </td>
	            </tr>
	            <tr>
	            	<td width="20%" align="right"><strong>手机号码</strong>：</td>
	                <td><input type="text" class="oaui-input-text" value="{$rs.cellphone}" name="cellphone"  /></td>
	            </tr>             
	            <tr>
	            	<td width="20%" align="right"><strong>QQ号码</strong>：</td>
	                <td><input type="text" class="oaui-input-text" value="{$rs.qq}" name="qq"  /></td>
	            </tr>
	            <if condition="power('edit')">
	            <tr>
                	<td>&nbsp;</td>
	            	<td><input type="button" id="submit" class="oaui-button oaui-button-submit" value=" 提 交 " /></td>
	            </tr>
	            </if>
	             
	        </table>
		</div>
	</form>
</body>
</html>