
	
$(function(){
	
	$('#account').focus();
	var s = $('#login_form');
	//ajax提交登陆表单
	$('form',s).submit(function(){
		
		var account = $.trim($("input[name='account']",this).val());
		if(account.length==0){
			dialog.tips('warning','请输入登录帐号!');
			return false;	
		}
		
		var password = $.trim($("input[name='password']",this).val());
		if(password.length==0){
			dialog.tips('warning','请输入登录密码!');
			return false;	
		}
		
		$.ajax({
			 url:$(this).attr('action'),
			type:$(this).attr('method'),
			data:$(this).serialize(),
			dataType:'json',
			context:this,
			beforeSend:function(){
				dialog.loading.show('验证中');
				$(':submit',this).attr('disabled',true);	
			},				
			complete:function(){
				$(':submit',this).attr('disabled',false);	
			}
			
		}).done(function(response){
			
			if(response.status=='success'){
				window.location = response.data;
			}else{
				dialog.loading.hide();
				dialog.tips(response.status,response.info);
			}
			
		}).fail(function(){
			dialog.loading.hide();
			dialog.tips('error','网络超时,登陆失败!');	
		});
		
		return false;	
	});
	
});


//placeholder 兼容性处理
$(function(){
	var s = $('#login_form');
	if( 'placeholder' in document.createElement('input') ){
		$('.placeholder',s).remove();
	}else{
	//模拟不支持placeholder的浏览器
		$('.text',s).each(function() {
			var val = $(this).val();
			if(val.length>0){
				$(this).siblings('.placeholder').hide();
			}else{
				$(this).siblings('.placeholder').show();	
			}            
		});
		$('.text',s).on('keyup blur mousedown mouseup',function(){
			var val = $(this).val();
			if(val.length>0){
				$(this).siblings('.placeholder').hide();
			}else{
				$(this).siblings('.placeholder').show();	
			}
		});		
			
	}
})

