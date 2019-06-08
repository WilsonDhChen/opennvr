var OAUI = (function(){
	//对外方法
	var OAMethod = {};
	//配置
	var options  = {
				Taskbar : '#OA_Taskbar',
				Pagetab : '#OA_Pagetab',
				Desktop : '#OA_Desktop',
				Loading : '#OA_Loading',
				AppSkin : '',
				AppPath : '',
				AppCtrl : 'Desktop',
				AppActs : {desktop:'deskapp',pagetab:'pagetab',nothing:'nothing'},
				
	};
	
	//定时器
	var loadingTimer = 0,resizeTimer = 0,retainTooler = 160;

	
	//获取URL
	function getUrlRouter(action,param){
		
		var url = options.AppPath+'/'+options.AppCtrl+'/'+(options.AppActs[action] || '');
		if(!param){
			return url;	
		}
		return $.isPlainObject(param) ?  (url+'?'+$.param(param)) : (url+'?'+param);
	};
	
	//设定侦听快捷键返回桌面的window对象
	function setDesktopBackWindow(pageWindow){
		try{
			$(pageWindow).on('keypress',function(event){
				//同时按住 shift+Z 快速返回桌面
				if(event.shiftKey && event.which==90){
					OAMethod.Taskbar.Switch('0');
				}
			});
		}catch(e){ }
	};
	
	//窗口大小改变
	function setResizeWindow(){
		$(options.Taskbar).parent().css('width',$(window).width()-retainTooler);
		resetChangeTaskbar();
	};
	
	//侦听任务栏变化，动态改变显示长度
	function resetChangeTaskbar(){
		//任务栏标签li 对象
		var $tabs = $(options.Taskbar).find('li');
		//父级容器宽度
		var wrapWidth = $(options.Taskbar).parent().width();
		//任务栏当前占据宽度
		var tabsWidth = (function(){
			var width = 0;
			$(this).each(function(){
				width+=$(this).outerWidth(true);
			});
			return width;		  	
		}).call($tabs);
		//任务栏选项卡最小宽度(是指标题.tasktab-text完全隐藏后所占据的宽度)
		var tabMinWidth = 61;
		//计算 tab text 标题显示最大宽度
		tabTextMaxWidth = Math.floor( wrapWidth / $tabs.length )- tabMinWidth;
		
		if(tabTextMaxWidth < 0){
			tabTextMaxWidth = 0;	
		}else if(tabTextMaxWidth > 80){
			tabTextMaxWidth = 80;
		}
		
		$(options.Taskbar).find('.tasktab-text').css('max-width',tabTextMaxWidth);
	
	};
	


	/* Method */
	
	OAMethod['App'] = {
		Run:function(app){
			var AppPage = $(options.Pagetab).find('#pagetab_'+app.appid);
			if(AppPage.length){
				OAMethod.App.Show(app.appid);
			}else{
				OAMethod.App.Load(app);	
			}
			
		},

		Load:function(app){
			OAMethod.Loading.Show('页面加载中');
			$.get(getUrlRouter('pagetab'),{appid:app.appid},function(pagetab){
				//隐藏其他的pagetab
				$(options.Pagetab).find('.pagetab-item').hide();
				//显示当前App的pagetab
				$(options.Pagetab).append(pagetab);
				//添加到任务栏选项卡
				OAMethod.Taskbar.Add(app);
			});
		},
		
		Show:function(appid){
			$(options.Pagetab).find('.pagetab-item').hide();
			$(options.Pagetab).find('#pagetab_'+appid).show();
			OAMethod.Taskbar.Active(appid);
		},

		//刷新指定标签页
		Refresh:function(appid){
			
			OAMethod.Loading.Show('页面加载中');
			
			var $iframe = $(options.Pagetab).find('#pagetab_'+appid).find('.pagetab-iframe');
			try{
				$iframe[0].contentWindow.location.reload();
			}catch(e){
				var src = $iframe.prop('src');
				$iframe.prop('src','about:blank');
				$iframe.prop('src',src);
			}
		},		
		//恢复最后一次被关闭的标签页
		Restore:function(){
			var app = $(options.Taskbar).data('restore');
			if(app){
				//重新打开上次被关闭的APP
				OAMethod.App.Run(app);
				//清除存储的上次被关闭的数据，因为已经恢复了。
				$(options.Taskbar).removeData('restore');
			}
		}		
					
	};
	
	OAMethod['Desktop'] = {

		//初始化
		Init:function(config){

			options = $.extend(true,{},options,config);

			//禁止鼠标右键
			$(document).on('contextmenu',function(){return false;});  
			//禁止选中
			$(document).on('selectstart',function(){return false;});
			//window resize
			$(window).resize(function(){
				if(resizeTimer){
					clearTimeout(resizeTimer);
				}
				resizeTimer = setTimeout(setResizeWindow,100);
				
			});
			setResizeWindow();
			//快捷键返回桌面(当前窗口主window对象)
			setDesktopBackWindow(window);

			/* 开始加载桌面 */
			//显示loading
			OAMethod.Loading.Show('桌面应用加载中');
			//加载桌面APP
			OAMethod.Desktop.Load();
			
			//任务栏上下文菜单(右键菜单)
			//任务栏标签菜单
			contextMenu( 'tasktab',
						[
							[
								{name:'关闭此标签页',icon:'close',handler:function(){
									
									$(this.target).find('.tasktab-close').triggerHandler('click');
									
								}},
								{id:'refresh',name:'刷新此标签页',icon:'refresh',handler:function(){
									
									OAMethod.App.Refresh($(this.target).data('appid'));	
									
								}},
								{id:'restore',name:'恢复上次关闭标签页',icon:'restore',handler:function(){
									OAMethod.App.Restore();
								}},
							],
							[
								{id:'close_other',name:'关闭其他标签页',icon:'close-other',handler:function(){
									$(options.Taskbar).find('.tasktab-app').not(this.target).each(function(){
										$(this).find('.tasktab-close').triggerHandler('click');
									});	
								}},
								{id:'close_left',name:'关闭左侧标签页',icon:'close-left',handler:function(){
									var index = $(options.Taskbar).find('.tasktab-app').index(this.target);
									$(options.Taskbar).find('.tasktab-app:lt('+index+')').each(function(){
										$(this).find('.tasktab-close').triggerHandler('click');
									});											
								}},
								{id:'close_right',name:'关闭右侧标签页',icon:'close-right',handler:function(){
									var index = $(options.Taskbar).find('.tasktab-app').index(this.target);
									$(options.Taskbar).find('.tasktab-app:gt('+index+')').each(function(){
										$(this).find('.tasktab-close').triggerHandler('click');
									});											
								}},
								{id:'close_all',name:'关闭全部标签页',icon:'close-all',handler:function(){
									dialog.confirm('确定要关闭全部标签页吗？',function(){
										OAMethod.Taskbar.CloseAll();	
									});
								}}
							],							
						],
						{proxy:options.Taskbar,context:'.tasktab-app'},
						{onshow:function(){
							//
							if($(options.Taskbar).data('restore')){
								this.menu.enabled('restore');
							}else{
								this.menu.disabled('restore');	
							}
							//
							$taskapp = $(options.Taskbar).find('.tasktab-app');
							//
							if($taskapp.not(this.target).length){
								this.menu.enabled('close_other');
							}else{
								this.menu.disabled('close_other');
							}
							//
							if($taskapp.index(this.target)>0){
								this.menu.enabled('close_left');
							}else{
								this.menu.disabled('close_left');
							}
							//
							if($taskapp.index(this.target)==($taskapp.length-1)){
								this.menu.disabled('close_right');
							}else{
								this.menu.enabled('close_right');
							}
						}}						
						);
			//任务栏菜单			
			contextMenu( 'taskbar',
						[
							[	{id:'desktop',name:'返回桌面',icon:'desktop',handler:function(){
									OAMethod.Taskbar.Switch('0');
								}},
								{id:'restore',name:'恢复上次关闭标签页',icon:'restore',handler:function(){
									OAMethod.App.Restore();
								}},
								{id:'close_all',name:'关闭全部标签页',icon:'close-all',handler:function(){
									dialog.confirm('确定要关闭全部标签页吗？',function(){
										OAMethod.Taskbar.CloseAll();	
									});
								}}
							]							
						],
						options.Taskbar,
						{
							onshow:function(){
								//
								if($(options.Taskbar).find('.tasktab-desktop').hasClass('tasktab-item-active')){
									this.menu.disabled('desktop');	
								}else{
									this.menu.enabled('desktop');
								}
								//
								if($(options.Taskbar).data('restore')){
									this.menu.enabled('restore');
								}else{
									this.menu.disabled('restore');	
								}
								//
								if($(options.Taskbar).find('.tasktab-app').length>0){
									this.menu.enabled('close_all');
								}else{
									this.menu.disabled('close_all');
								}																
									
							}	
						}
						);
	
		},
		
		Load:function(){
			//加载桌面
			$.get(getUrlRouter('desktop'),function(desktop){
				$(options.Desktop).html(desktop);
				OAMethod.Loading.Hide();
				//icon onerror 事件
				$(options.Desktop).find('.app-icon').on('error',function(){
					$(this).off('error');
					$(this).prop('src',options.AppSkin+'/image/tasktab_icon_48.png');
					return true;
				});					
			});			
		},
		
		Logout:function(){
			
			dialog.confirm('确定要退出登录本系统吗？',function(){
				window.location = options.AppPath+'/Logout';
			});
				
		}
				

	};
	
	OAMethod['Taskbar'] = {
		
		Add:function(app){
			$(options.Taskbar).find('.tasktab-item-active').removeClass('tasktab-item-active');
			var tpl = '<li class="tasktab-item tasktab-item-active tasktab-app" id="taskbar_'+app.appid+'" data-appid="'+app.appid+'" onclick="OAUI.Taskbar.Switch(\''+app.appid+'\')" title="'+app.name+'"><i class=" tasktab-icon '+app.icon+'"></i><span class="tasktab-text">'+app.name+'</span><span class="tasktab-close" onclick="OAUI.Taskbar.Close(event,\''+app.appid+'\')" title="关闭">×</span></li>';
			$(options.Taskbar).append(tpl);
			//保存当前标签的app对象数据
			$(options.Taskbar).find('#taskbar_'+app.appid).data('app',app);
			//
			resetChangeTaskbar();
		},
		
		Active:function(appid){
			$(options.Taskbar).find('.tasktab-item').removeClass('tasktab-item-active');
			$(options.Taskbar).find('#taskbar_'+appid).addClass('tasktab-item-active');
		},
		
		Switch:function(appid){
			
			//如果点击的是当前active激活任务栏标签者不做任何操作
			if( $(options.Taskbar).find('#taskbar_'+appid).hasClass('tasktab-item-active') ){
			  	return false;
			}
						
			OAMethod.Taskbar.Active(appid);
			OAMethod.Pagetab.Active(appid);

		},
		
		Find:function(appid){
			return $(options.Taskbar).find('#taskbar_'+appid);
		},
		
		Close:function(event,appid){
			
			if(!parseInt(appid)){
				return false;
			}
			
			var $taskbar = $(options.Taskbar).find('#taskbar_'+appid);
			//保存当前被关闭的taskbar 留作恢复使用
			$(options.Taskbar).data('restore',$taskbar.data('app'));
			//
			var isActive = $taskbar.hasClass('tasktab-item-active');
			if(isActive){
				var index = $taskbar.index();
				if(index>0){
					var actid = $(options.Taskbar).find('.tasktab-item:eq('+(index-1)+')').data('appid');
				}else{
					var actid = $(options.Taskbar).find('.tasktab-item:eq('+(index+1)+')').data('appid');
				}
			}
			/*移除现有taskbar,pagetab*/
			var $pagetab = $(options.Pagetab).find('#pagetab_'+appid);
			//合理清除pagetab里面iframe防止内存泄漏、不释放等问题
			var $iframe = $pagetab.find('.pagetab-iframe');
			$iframe.off();
			$iframe.prop('src','about:blank');
			try{
				with($iframe[0]){
					contentWindow.document.write('');
					contentWindow.document.clear();
					contentWindow.close();
				}
			}catch(e){ }
			$iframe.remove();
			$pagetab.remove();
			$taskbar.remove();
			//
			if(isActive){
				OAMethod.Taskbar.Switch(actid);
			}
			//
			resetChangeTaskbar();
			//阻止冒泡到taskbar点击事件
			event.stopPropagation();
			return false;
			
		},
		CloseAll:function(){
			$(options.Taskbar).find('.tasktab-app').each(function(){
				$(this).find('.tasktab-close').triggerHandler('click');
			});			
		},
		Icon:function(img){
			img.onerror = null;
			img.src = options.AppSkin+'/image/tasktab_icon_32.png';
			return true;
		}
			
	};
	
	OAMethod['Pagetab'] = {

		Open:function(that){
			//显示pagetab加载loading
			OAMethod.Loading.Show('页面加载中');
			//
			var $sidetab = $(that).is('dl') ? $(that) : $(that).parents('.sidetab-item');
			var $pagetab = $(that).parents('.pagetab-item');
			var $iframe  = $pagetab.find('.pagetab-iframe');
			var pagetab_src = $(that).data('iframe') || getUrlRouter('nothing',{appid:$pagetab.data('appid')});
			$iframe.prop('src',pagetab_src);
			//
			$pagetab.find('.sidetab-item').removeClass('sidetab-item-active');
			$sidetab.addClass('sidetab-item-active');
		},
		Load:function(iframe){
			//隐藏pagetab加载loading
			OAMethod.Loading.Hide();
			//快捷键返回桌面(pagetab iframe window对象)
			setDesktopBackWindow(iframe.contentWindow);
		},
		Active:function(appid){
			$(options.Pagetab).find('.pagetab-item').hide();
			$(options.Pagetab).find('#pagetab_'+appid).show();
		},
		Icon:function(img){
			img.onerror = null;
			img.src = options.AppSkin+'/image/sidetab_icon.png';
			return true;
		}		
	};
	
	OAMethod['Loading'] = {

		Show:function(text){
			var html = (text || '加载中')+'<span class="loading-process"></span>';
			$(options.Loading).find('div').html(html);	
			$(options.Loading).stop().fadeIn(500);
			loadingTimer = setInterval(function(){
				var $process = $(options.Loading).find('.loading-process');
				if($process.text().length==5){
					$process.text('');
				}else{
					$process.text($process.text()+'·');
				}
			},300);
		},
		Hide:function(){
			$(options.Loading).stop().hide().css('opacity',0);
			$(options.Loading).find('div').html('');
			clearInterval(loadingTimer);
		}
	};	
	
	
	return OAMethod;
	
})();
