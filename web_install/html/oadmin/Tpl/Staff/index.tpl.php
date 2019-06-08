<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户列表</title>
{:W('Css','global_widget')}
{:W('Skin','widget')}
<style type="text/css">
body{ padding:64px 0 40px 0;}
.username{ max-width:70px; display:block;}
.regtime{ font-size:10px; font-family:Tahoma, Geneva, sans-serif; width:85px;}
</style>
{:W('jQuery')}
{:W('Dialog')}
<script type="text/javascript">
$(function(){
	//{//记录隔行换色}
	$('.widget-com-grid .grid-list tr:even').addClass('even');
	//{//记录双击高亮色}
	$('.widget-com-grid .grid-list tbody tr').dblclick(function(){
		if($(this).hasClass('selected')){
			$(this).removeClass('selected')
		}else{
			$(this).addClass('selected')	
		}
	});
})

function staff_delete(staff_id){
	
	if(!staff_id){
		dialog.tips('error','参数丢失');
		return false;	
	}
	//
	dialog.confirm('确认要删除此员工吗？',function(){
		$.ajax({
			
			 url:'__URL__/delete',
			 type:'POST',
			 data:{staff_id:staff_id},
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
					dialog.tips('error','网络故障 请重试');	
			}	
		})		
	})
	
}
</script>
</head>

<body>
<div class="widget-com-grid">
<div class="grid-search">
	<form action="">
    	<table>
	        <tr>
	        	<td><label for="cellphone">手机</label><input type="text" name="cellphone" id="cellphone" class="text" value="{$cellphone}"  /></td>
	    		<td><label for="qq">QQ号码</label><input type="text" name="qq" id="qq" class="text"  value="{$qq}"   /></td>
	        	<td><label for="username">帐号</label><input type="text" name="username" id="username" class="text"  value="{$username}"    /></td>
	        	<td><label for="realname">真实姓名</label><input type="text" name="realname" id="realname" class="text"  value="{$realname}"    /></td>
	        </tr>
			<tr>
				<td>
					<label for="gender">性别</label>
					<select name="gender" id="gender">
						<option value="">全部</option>
						<option value="Male" <?php echo $gender=='Male'?'selected':''?> >男性</option>
						<option value="Female" <?php echo $gender=='Female'?'selected':''?> >女性</option>
						<option value="UnKnown" <?php echo $gender=='UnKnown'?'selected':''?> >未知</option>
					</select>
				</td>
				<td>
					<label for="role">员工角色</label>
					<select name="role" id="role">
						<option value="0">全部</option>
						<volist name="roles" id="vo">
							<option value="{$vo.role_id}" <?php echo $role==$vo['role_id']?'selected':''?> >{$vo.role_name}</option>
						</volist>
					</select>
				</td>
				<td>
					<label for="job_status">员工状态</label>
					<select name="job_status" id="job_status">
						<option value="">全部</option>
						<option value="1" <?php echo $job_status=="1"?'selected':''?> >在职</option>
						<option value="0" <?php echo $job_status=="0"?'selected':''?> >离职</option>
					</select>
				</td>
				<notempty name="search">
					<td align="right"><input type="button" value=" 退出搜索 " onclick="window.location='__URL__/index'" /></td>
				</notempty>
				<td><input type="hidden" name="search" value="1" /><input type="submit" class="submit" value=" 搜 索 " /></td>
			</tr>
        </table>

    </form>
</div>
<table class="grid-list">
	<thead>
    	<tr>
            <th>{:grid_sort('工号','staff_id')}</th>
    		<th align="left">帐号</th>
            <th align="left">姓名</th>
            <th>性别</th>
            <th align="left">角色</th>
            <th>手机</th>
            <th align="left">QQ</th>
            <th>{:grid_sort('入职时间','entry_date')}</th>
            <th>状态</th>
            <th align="left">操作</th>
        </tr>
    </thead>
    <tbody>
    	<volist name="grid_data" id="vo">
    	<tr>
    		<td align="center">{$vo.staff_id}</td>
            <td title="{$vo.username}"><span class="username to-e">{$vo.username}</span></td>
            <td>{$vo.realname}</td>
            <td align="center">
            	<switch name="vo['gender']">
                	<case value="UnKnown"><span class="unknown">未知</span></case>
                    <case value="Female">女性</case>
                    <case value="Male">男性</case>
                </switch>            
            </td>                        
            <td>{$vo.roles}</td>
            <td align="center">{$vo.cellphone|default=''}</td>
            <td>{$vo.qq|default=''}</td>
            <td align="center" class="regtime"><span>{$vo.entry_date}</span></td>
            <td align="center">{:$vo['job_status']==1?'<span style="color:#093">在职</span>':'<span style="color:#c00">离职</span>'}</td>
            <td>
            	<if condition="$vo['isadmin']">
                	<if condition="$super_admin">
	            	<a href="__APP__/staff/edit?staff_id={$vo.staff_id}" title="员工信息修改" class="grid-func func-edit">编辑</a>
                    <if condition="$staff['staff_id'] neq $vo['staff_id']">
                    <a href="javascript:;" title="删除" onclick="staff_delete({$vo.staff_id})" class="grid-func func-delete">删除</a>
                    </if> 
	                <a href="javascript:;" title="员工密码修改" onclick="dialog.frame('__APP__/staff/password?staff_id={$vo.staff_id}','{$vo.username}-用户密码修改',true,'iframe')"  class="grid-func func-key">密码</a>
                    </if>
				<else />	
	            	<if condition="power('edit')">
	            	<a href="__APP__/staff/edit?staff_id={$vo.staff_id}" title="员工信息修改" class="grid-func func-edit">编辑</a>
	                </if>
	                <if condition="power('delete') AND ($staff['staff_id'] neq $vo['staff_id'])">
	                <a href="javascript:;" title="删除" onclick="staff_delete({$vo.staff_id})" class="grid-func func-delete">删除</a>
	                </if>                    
	                <if condition="power('password')">
	                <a href="javascript:;" title="员工密码修改" onclick="dialog.frame('__APP__/staff/password?staff_id={$vo.staff_id}','{$vo.username}-用户密码修改',false,'iframe')"  class="grid-func func-key">密码</a>
	                </if>                
                </if>
            </td>
    	</tr>
        </volist>
        <empty name="grid_data">
        	<tr><td colspan="99" align="center">
        	<notempty name="search">
            	没有找到符合条件的员工	
            <else />
            	暂无任何员工信息
            </notempty>
        	</td></tr>
        </empty>        
    </tbody>
</table>
<div class="grid-page">
{$page_html}
</div>
</div>
</body>
</html>