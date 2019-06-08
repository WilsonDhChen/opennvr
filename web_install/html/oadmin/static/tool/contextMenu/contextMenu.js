var contextMenu = (function($,window,undefined){
	//document 对象
	var doc = window.document;
	//私有方法
	var pim = {};
	//ContextMenu 容器
	var con = 'OAUI_ContextMenu';
	//显示状态，是否已经显示ContextMenu
	var showStatus = false;
	//触发ContextMenu 目标源对象
	var target;
	//ContextMenu handler 索引
	var handlerIndex = 0;
	//事件侦听回调
	var listener = {};
	//事件回调函数this对象
	var caller = {};
	//menu handlers data
	$(doc).data('handlers',[]);
	//鼠标左键点击当前页面隐藏菜单
	$(doc).on('click',function(){
		pim.hide();
	});
	//window对象失去焦点隐藏ContextMenu
	$(window).on('blur',function() {
		pim.hide();
	});	
	
	
	/* Private */
	pim.event = function(event){
		//
		target = this;
		listener = event.data.listener || {};
		//检测如果是首次触发ContextMenu 创建html结构
		if($('#'+con+'_'+event.data.namespace).length==0){
	  		//ContextMenu 容器、遮罩层是否存在
	  		if($('#'+con).length==0){
				$('<div>',{'id':con}).appendTo('body');
	  		}
			//创建菜单DOM
			pim.create(event.data.namespace,event.data.menu);
		}
		//
		caller.target = target;
		caller.menu = {	
						find    : function(id){ return pim.find(event.data.namespace,id)     },
						disabled : function(id){ return pim.disabled(event.data.namespace,id)  },
						enabled : function(id){ return pim.enabled(event.data.namespace,id)  },
						remove  : function(id){ return pim.remove(event.data.namespace,id)	 }
					   };
		if($.isFunction(listener.onshow) && listener.onshow.call(caller,event)===false){
			return false;
		}
		pim.show(event);
		return false;
	};
	pim.create = function(namespace,menu){
		var html = '<div id="'+con+'_'+namespace+'" class="oaui-contextmenu oaui-contextmenu-'+namespace+'"><ul>';
		html+=pim.nodes(menu,namespace);
		html+='</ul></div>';
		$('#'+con).append(html);
		html = null;
		//移除多余的分割线
		$('#'+con+'_'+namespace).find('li:last-child.oaui-contextmenu-line').prev('.oaui-contextmenu-line').addBack().remove();
		//鼠标经过子节点菜单处理
		$('#'+con+'_'+namespace).find('.oaui-contextmenu-menu').on('mouseenter',function(event){
			//隐藏所有子节点
			$(this).parent().find('.oaui-contextmenu-node').children('ul').hide();
			//如果当前鼠标经过的是子节点，则显示当前子节点菜单
			if($(this).hasClass('oaui-contextmenu-node')){
				//计算 子菜单 显示方位
				var $node 	= $(this).children('ul');
				var nodeSize= {width:$node.width(),height:$node.height()}
				var winSize = {width:$(window).width(),height:$(window).height()}
				var diffSize= {X:(winSize.width-event.clientX)-(nodeSize.width*0.75),Y:(winSize.height-event.clientY)-(nodeSize.width*0.75)}
				var nodePos = {};
				
				if(diffSize.X>nodeSize.width){
					nodePos.left = nodeSize.width;
				}else{
					nodePos.left = nodeSize.width*-1;
				}
				
				if(diffSize.Y>nodeSize.height){
					nodePos.top = 0;
				}else{
					nodePos.top = nodeSize.height*-1+28;
				}				

				$node.css(nodePos).show();	
			}
		});
		
		//menu hanlder
		$('#'+con+'_'+namespace).find('.oaui-contextmenu-handler').on('click',function(){
			if($(this).hasClass('oaui-contextmenu-menu-disabled')){
				return false;	
			}
			var handlers = $(doc).data('handlers');
			handlers[$(this).data('handler')].call(caller);
		});			
							
		
		
	};
	pim.nodes = function(menu,namespace){
		var nodes = '';
		if($.isArray(menu)){
			$.each(menu,function(i,item){
				nodes+=pim.nodes(item,namespace);
			});
			nodes+='<li class="oaui-contextmenu-line"><hr/></li>';
		}else if($.isPlainObject(menu)){
			var menuId = menu.id ? ' id="ContextMenu_NodeMenu_'+namespace+'_'+menu.id+'" ' : '';
			if($.isArray(menu.menu)){
				nodes+='<li class="oaui-contextmenu-menu oaui-contextmenu-node"'+menuId+'><a class="oaui-contextmenu-item" title="'+menu.name+'"><i class="oaui-contextmenu-icon'+(menu.icon?' oaui-contextmenu-icon-'+menu.icon:'')+'"></i><span class="oaui-contextmenu-name">'+menu.name+'</span><i class="oaui-contextmenu-arrow"></i></a>';
				nodes+= '<ul>'+pim.nodes(menu.menu,namespace)+'</ul>';
			}else{
				if($.type(menu.handler)=='function'){
					var handlers = $(doc).data('handlers');
					handlers[handlerIndex] = menu.handler;
					$(doc).data('handlers',handlers);
					nodes+='<li class="oaui-contextmenu-menu oaui-contextmenu-handler" data-handler="'+handlerIndex+'"'+menuId+'><a class="oaui-contextmenu-item" title="'+menu.name+'"><i class="oaui-contextmenu-icon'+(menu.icon?' oaui-contextmenu-icon-'+menu.icon:'')+'"></i><span class="oaui-contextmenu-name">'+menu.name+'</span></a>';
					handlerIndex++;
				}else{
					nodes+='<li class="oaui-contextmenu-menu"'+menuId+'><a class="oaui-contextmenu-item" title="'+menu.name+'"><i class="oaui-contextmenu-icon'+(menu.icon?' oaui-contextmenu-icon-'+menu.icon:'')+'"></i><span class="oaui-contextmenu-name">'+menu.name+'</span></a>';
				}
			
			}
			nodes+='</li>';					
		}
		
		return nodes;
	};
	pim.show = function(event){
		$('#'+con).show();
		$('#'+con).find('.oaui-contextmenu').hide();
		$('#'+con+'_'+event.data.namespace).css(pim.pos(event)).show();
		//
		showStatus = true;
	};
	pim.hide = function(){
		//如果当前不是已经显示的状态 直接返回
		if(!showStatus) return false;
		//
		$('#'+con).find('.oaui-contextmenu-node ul').hide();
		$('#'+con).find('.oaui-contextmenu').hide();
		$('#'+con).hide();
		if($.isFunction(listener.onhide)){
			listener.onhide.call(caller);
		}
		//
		showStatus = false;		
	};
	pim.pos = function(event){
		//计算顶级ContextMenu 显示位置
		var docScroll = {left:$(doc).scrollLeft(),top:$(doc).scrollTop()};
		var winSize   = {width:$(window).width(),height:$(window).height()}
		var diffSize  = {X:winSize.width-event.clientX,Y:winSize.height-event.clientY}
		var menuSize = {width:$('#'+con+'_'+event.data.namespace).width(),height:$('#'+con+'_'+event.data.namespace).height()}
		var pos = {};
		if(diffSize.X>menuSize.width){
			pos.left = event.clientX+docScroll.left;
		}else{
			pos.left = event.clientX+docScroll.left-menuSize.width;
		}
		
		if(diffSize.Y>menuSize.height){
			pos.top = event.clientY+docScroll.top;
		}else{
			pos.top = event.clientY+docScroll.top-menuSize.height;
		}		

		return pos;
	};
	pim.find = function(namespace,id){
		return $('#ContextMenu_NodeMenu_'+namespace+'_'+id)[0];
	};	
	pim.disabled = function(namespace,id){
		var node = pim.find(namespace,id);
		if(node){
			$(node).addClass('oaui-contextmenu-menu-disabled');
			return true;
		}
		return false;
		
		
	};
	pim.enabled = function(namespace,id){
		var node = pim.find(namespace,id);
		if(node){
			$(node).removeClass('oaui-contextmenu-menu-disabled');
			return true;
		}
		return false;
	};
	pim.remove = function(namespace,id){
		$(pim.find(namespace,id)).remove();
		return true;
	};		
	
		
	
	
	
	return function(namespace,menu,selector,listener){
		//
		var data = {namespace:namespace,menu:menu,listener:listener};
		
		if($.type(selector)=='undefined'){
			$(doc).on('contextmenu',data,pim.event);
		}else if($.isPlainObject(selector)){
			$(selector.proxy).on('contextmenu',selector.context,data,pim.event);
		}else{
			$(selector).on('contextmenu',data,pim.event);
		}
	}
			
})(jQuery,window);