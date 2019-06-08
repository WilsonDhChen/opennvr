<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>编辑员工</title>
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
			<table align="center">
	        	<tr>
	            	<td width="20%" align="right"><strong>员工工号</strong>：</td>
	                <td><strong>{$rs.staff_id}</strong></td>
	            </tr>        
	        	<tr>
	            	<td width="20%" align="right"><strong>员工帐号</strong>：</td>
	                <td><input type="text" class="oaui-input-text" name="username" value="{$rs.username}" /></td>
	            </tr>
	            <tr>
	            	<td width="20%" align="right"><strong>员工姓名</strong>：</td>
	                <td><input type="text" class="oaui-input-text" value="{$rs.realname}" name="realname"  /></td>
	            </tr>
	            <tr>
	            	<td width="20%" align="right"><strong>员工性别</strong>：</td>
	                <td>
	                    <input type="radio" name="gender" value="Male" id="gender_male" <?php echo $rs['gender']=='Male'?'checked':'' ?> /><label for="gender_male">男性</label> 　
	                    <input type="radio" name="gender" value="Female" id="gender_female" <?php echo $rs['gender']=='Female'?'checked':'' ?>  /><label for="gender_female">女性</label>　
	
	                </td>
	            </tr>
	            <tr>
	            	<td width="20%" align="right"><strong>入职日期</strong>：</td>
	                <td><input  class="oaui-input-text Wdate" readonly="readonly" autocomplete="off" onFocus="WdatePicker({isShowClear:false,isShowToday:true,errDealMode:-1})" type="text" name="entry_date" value="{$rs.entry_date}" /></td>
	            </tr>
	            <tr>
	            	<td width="20%" align="right"><strong>手机号码</strong>：</td>
	                <td><input type="text" class="oaui-input-text" value="{$rs.cellphone}" name="cellphone"  /></td>
	            </tr>             
	            <tr>
	            	<td width="20%" align="right"><strong>QQ号码</strong>：</td>
	                <td><input type="text" class="oaui-input-text" value="{$rs.qq}" name="qq"  /></td>
	            </tr>
	            <tr>
	            	<td width="20%" align="right"><strong>Email</strong>：</td>
	                <td><input type="text" class="oaui-input-text" value="{$rs.email}" name="email"  /></td>
	            </tr>
	                       
	            <tr>
	            	<td width="20%" align="right"><strong>权限角色</strong>：</td>
	                <td>
	                	<select name="roles[]" size="5"  multiple="multiple" id="roles" class="oaui-select oaui-select-multiple">
	                        <volist name="roles" id="vo">
	                		<option value="{$vo.role_id}" <?php echo in_array($vo['role_id'],$role)?'selected':''?> >{$vo.role_name}</option>
	                        </volist>
	                    </select>
	                    <span class="tips">使用ctrl键权限角色可多选<notempty name="super_admin">(超级管理员角色除外)</notempty></span>
	              </td>
	            </tr>            
	            <tr>
	            	<td width="20%" align="right"><strong>员工状态</strong>：</td>
	                <td>
	                    <input type="radio" name="job_status" value="1" id="job_status_1" <?php echo $rs['job_status']=='1'?'checked':'' ?> /><label for="job_status_1">在职</label> 　
	                    <input type="radio" name="job_status" value="0" id="job_status_0" <?php echo $rs['job_status']=='0'?'checked':'' ?>/><label for="job_status_0">离职</label>　
	                </td>
	            </tr>
	            
	            <tr>
	            	<td>&nbsp;</td>
	            	<td>
                    	<input type="button" id="submit" class="oaui-button oaui-button-submit" value=" 提 交 " />
                        <hr class="oaui-space oaui-space-x10"/>
                    	<input type="button" class="oaui-button oaui-button-common" onclick="window.history.back()" value=" 返 回 " />                        
                    </td>
	            </tr>
	            
	             
	        </table>
        </div>
	</form>
		
</body>
</html>