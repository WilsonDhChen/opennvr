<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加员工</title>
{:W('Css','global_widget')}
{:W('Skin','widget')}
{:W('jQuery')}
{:W('Dialog')}
{:W('Calendar')}
<script type="text/javascript">

$(function(){
	
	$('#submit').click(function(){
		
				var username = $.trim($("input[name='username']").val());
				
				if(username.length==0){
					dialog.tips('error','员工帐号不能为空');
					return false;						
				}
				
				if(!/^[a-z0-9\-_]+$/i.test(username)){
					dialog.tips('error','员工帐号只能由字母、数字、减号和下划线组成');
					return false;	
				}
				
				var length = username.length;
				if(length<2 || length>30){
					dialog.tips('error','员工帐号长度在2~30个字符之间');
					return false;
				}								
								
				
				if($("#roles option:selected").length==0){
					dialog.tips('error','请选择员工权限角色');	
					return false;
				}
				
				$.ajax({
					  url:'__URL__/add_post',
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
			<table align="center">
	        	<tr>
	            	<td width="20%" align="right"><strong>员工帐号</strong>：</td>
	                <td><input type="text" class="oaui-input-text" value="" name="username" /></td>
	            </tr>
	        	<tr>
	            	<td width="20%" align="right"><strong>登录密码</strong>：</td>
	                <td><input type="text" class="oaui-input-text" value="123456" name="password" /></td>
	            </tr>            
	            <tr>
	            	<td width="20%" align="right"><strong>员工姓名</strong>：</td>
	                <td><input type="text" class="oaui-input-text" value="" name="realname"  /></td>
	            </tr>
	            <tr>
	            	<td width="20%" align="right"><strong>员工性别</strong>：</td>
	                <td>
	                    <input type="radio" name="gender" value="Male" id="gender_male"/><label for="gender_male">男性</label> 　
	                    <input type="radio" name="gender" value="Female" id="gender_female"  /><label for="gender_female">女性</label>　
	                </td>
	            </tr>
	            <tr>
	            	<td width="20%" align="right"><strong>入职日期</strong>：</td>
	                <td><input  class="oaui-input-text Wdate" readonly="readonly" autocomplete="off" onFocus="WdatePicker({isShowClear:false,isShowToday:true,errDealMode:-1})" type="text" name="entry_date" value="{:date('Y-m-d')}" /></td>
	            </tr>
	            <tr>
	            	<td width="20%" align="right"><strong>手机号码</strong>：</td>
	                <td><input type="text" class="oaui-input-text" value="" name="cellphone"  /></td>
	            </tr>             
	            <tr>
	            	<td width="20%" align="right"><strong>QQ号码</strong>：</td>
	                <td><input type="text" class="oaui-input-text" value="" name="qq"  /></td>
	            </tr>
	            <tr>
	            	<td width="20%" align="right"><strong>Email</strong>：</td>
	                <td><input type="text" class="oaui-input-text" value="" name="email"  /></td>
	            </tr>                          
	          
	            <tr>
	            	<td width="20%" align="right"><strong>权限角色</strong>：</td>
	                <td>
	                	<select name="roles[]" size="5"  multiple="multiple" id="roles" class="oaui-select oaui-select-multiple">
	                        <volist name="roles" id="vo">
	                		<option value="{$vo.role_id}" <?php echo $role==$vo['role_id']?'selected':''?> >{$vo.role_name}</option>
	                        </volist>
	                    </select>
	                    <span class="oaui-input-tips">使用ctrl键权限角色可多选<notempty name="super_admin">(超级管理员角色除外)</notempty></span>
	              </td>
	            </tr>            
	            <tr>
	            	<td width="20%" align="right"><strong>员工状态</strong>：</td>
	                <td>
	                    <input type="radio" name="job_status" value="1" id="job_status_1" checked="checked" /><label for="job_status_1">在职</label> 　
	                    <input type="radio" name="job_status" value="0" id="job_status_0" /><label for="job_status_0">离职</label>　
	                </td>
	            </tr>
	            
	            <tr>
	            	<td>&nbsp;</td>
	            	<td><input type="button" id="submit" class="oaui-button oaui-button-submit" value=" 提 交 " /></td>
	            </tr>
	            
	             
	        </table>
        </div>
	</form>
</body>
</html>