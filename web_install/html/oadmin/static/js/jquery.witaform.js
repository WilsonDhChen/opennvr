/**
 +---------------------------------------------------------------------------
 | jQuery witaForm 智能异步表单插件
 +---------------------------------------------------------------------------
 | info		: 支持表单验证、文件上传
 | version	： 1.0.3
 | updatedAt: 2017-05-11
 +---------------------------------------------------------------------------
 */
 
(function($){
	
		//命名空间
	var namespace = 'witaform',
		//配置选项
		options;
	
	$.fn.witaForm = function(custom_options){
		
		var default_options = {
				  action : '',   			//表单提交地址 默认读取表单 action 此处设置优先级高于表单设置
				  method : '', 				//表单提交类型 默认读取表单 method 此处设置优先级高于表单设置
					type : '', 				//远程响应数据类型 默认读取表单 data-type 此处设置优先级高于表单设置（默认为json数据）
			      submit : $.noop,			//表单提交前 接口  return false 将不提交表单
				complete : $.noop,  		//表单提交完成接口
				 success : $.noop,			//表单提交成功 接口
				   error : $.noop,			//表单提交出错 接口				 
				   alert : null, 			//提醒方式 默认使用 alert， 可自定义提示方式， alert:function(tips){  yourAlert(tips)  }
				    rule : null,			//验证规则
			   		tips : null,			//验证规则提示
			      signal : 'isAjaxRequest'  // witaform 表单提交标记字段名称(如果不想添加表单标记 可设置为 false)
			};
		//合并默认选项和用户自定义选项
  		options = $.extend(true,{},default_options,custom_options);
		
		//遍历处理所有表单
		this.each(function(){
				
			_initForm(this);
			
		});
		
	
		
	};
	
	//初始化表单
	function _initForm(the){
		//排除非表单对象元素
		if( !$(the).is('form') )return false;
		//创建表单标识
		$(the).attr('data-'+namespace+'-id',$('form').index(the)+1);
		
		//添加表单标记字段
		if(options.signal) $(the).append('<input type="hidden" name="'+options.signal+'" value="true" />');
		//获取当前表单属性
		var attrs = _getFormAttrs(the);
		//检测form enctype 决定使用表单提交方式
		if( attrs.enctype =='multipart/form-data' ){
			//如果表单允许文件上传 创建表单提交目标iframe
			_createTargetFrame(the);
			//设置最终表单属性值
			_setFormFinalAttrs(the,attrs);
			
		}
		
		_postForm(the,attrs);
		
	}
	
	//
	function _postForm(the,attrs){
		
		//表单提交事件
		$(the).submit(function(event){

			//
			if( _validateFormHandler(this)===false || _submitHandler(this)===false ){
				_completeHandler(this);
				return false;	
			};
			
			if( attrs.enctype == 'multipart/form-data' ){
				_postFormByFrame(this,attrs);
			}else{
				_postFormByAjax(this,attrs);
				return false;
			}
				
		});		
	}
	
	
	//使用ajax提交表单
	function _postFormByAjax(the,attrs){
		
		$.ajax({
				url		: attrs.action,
				type	: attrs.method,
				data	: $(the).serialize(),
			dataType	: attrs.type,
			success		: options.success,
			error		: options.error,
			complete	: function(){  _completeHandler(the); }
			
		})
		
	}
	
	//multipart/form-data方式表单 使用隐藏iframe提交
	function _postFormByFrame(the,attrs){
		//获取target iframe name
		var name = namespace+'_frame_'+$(the).attr('data-'+namespace+'-id');
		//表单提交后 target iframe 处理事件
		$("iframe[name='"+name+"']").off('load').on('load',function(){
			// complete事件
			 _completeHandler(the);
			try{
				var responseText = $(this).contents().text() ,
					responseData;
				switch(attrs.type){
					case 'json' : responseData = $.parseJSON(responseText); break;
					case 'xml'  : responseData = $.parseXML(responseText); break;
					default 	: responseData = responseText;
				}
				// sucess事件
				options.success.call(the,responseData);
			}catch(e){
				// error 事件
				options.error.call(the);	
			}
			
		});
		
		
	}
	
	//form表单验证
	function _validateFormHandler(the){
		
		var rule = options.rule;
		// 如果rule不是对象 或是 为空对象 则不验证，直接返回 true
		if( !_isValidObject(rule) ) return true;
		
		//遍历所有规则
		for(field in rule){
			//object rule
			if( _isValidObject(rule[field]) ){
				
				for(key in rule[field]){
					if( !_ruleHandler({field:field,key:key,val:rule[field][key]},the) ){
						return false;
						break;
					}
				}
			//string rule
			}else if( _isValidString(rule[field]) ){
				if( _ruleHandler({field:field,key:rule[field],val:true},the) === false ){
					return false;
				}
			
			//function rule	
			}else if($.isFunction(rule[field])){
				if( !_ruleHandler({field:field,key:'callback',val:rule[field]},the) ){
					return false;
				}				  	
			}
		}
		
		return true;
	}
	
	//检测是否是一个有效的对象类型，{}或new Object 并且非空
	function _isValidObject(obj){
		return $.isPlainObject(obj) && !$.isEmptyObject(obj);
	}
	//检测是否是一个字符类型 并且非空
	function _isValidString(str){
		return typeof str ==='string' && $.trim(str.length) > 0;
	}
	
	//逗号分隔字符串转数组
	function _castToArray(data){
		if($.isArray(data)) return data;
		if( _isValidString(data) && data.indexOf(',',1) ){
			return data.split(',');
		};
		return [data];
	}
	
	//submit 处理
	function _submitHandler(the){
		$(':submit',the).attr('disabled',true);
		return options.submit.call(the);
	}
	
	//complete 处理
	function _completeHandler(the){
		$(':submit',the).attr('disabled',false);
		return options.complete.call(the);
	}
	
	//获取form 属性
	function _getFormAttrs(the){
		
		var attrs = {};
		attrs.enctype  	=  $(the).attr('enctype')  || '' ;
		attrs.action   	=  options.action  ?  options.action  : ($(the).attr('action')     || window.location.href);
		attrs.method   	=  options.method  ?  options.method  : ($(the).attr('method')     || 'get' );
		attrs.type		=  options.type    ?  options.type    : ($(the).attr('data-type')  || 'json' );
		return attrs;
		
	}
	//multipart/form-data方式表单 创建提交target
	function _createTargetFrame(the){
		
		var name = namespace+'_frame_'+$(the).attr('data-'+namespace+'-id');
		$(the).append('<iframe frameborder="0" width="0" height="0" src="about:blank" name="'+name+'" style="display:none;"></iframe>');
		$(the).attr('target',name);
		
	}
	
	//multipart/form-data方式表单 设置表单提交最终属性值
	function _setFormFinalAttrs(the,attrs){
		
		$(the).attr('action',attrs.action);
		$(the).attr('method',attrs.method);
		//移除form onsubmit 事件
		$(the).removeAttr('onsubmit');
	}
	
	//验证规则处理
	function _ruleHandler(rule,the){

		var input  = $(":input[name='"+rule.field+"']",the),
			result = _rule[rule.key].call(input,rule.val);
		if(rule.val=== false || result === true ) return true;
		_tipsHandler(rule,input);
		return false;
	}
	
	//验证提示处理
	function _tipsHandler(rule,input){
		var tips_msg = _getTipsMsg(rule,input);
		if($.isFunction(options.alert)){
			options.alert.call(input,tips_msg);
		}else{
			window.alert(tips_msg);
		}
	}
	
	//获取提示信息内容
	function _getTipsMsg(rule,input){
		
		var fdtips = _isValidObject(options.tips) ?  options.tips[rule.field] : false ,
			tips_msg;
		
		if( _isValidString(fdtips) ){
			tips_msg = fdtips;
		}else if( _isValidObject(fdtips) && _isValidString(fdtips[rule.key]) ){
			tips_msg = fdtips[rule.key];
		}else{
			tips_msg = _tips[rule.key];
		}
		
		var fieldName = $(input).attr('title') ? $(input).attr('title') : rule.field;
		return tips_msg.replace(/\{field\}/gi,fieldName)
			 	 	   .replace(/\{(\d)\}/gi,function(o,i){ var d = _castToArray(rule.val);return d[i]; } );
			
	}
	
	//设置特殊规则提示表达式
	function _setSpecialExpTips(field,rule,expVar,expVal){
		//是否自定义tips
		if( !_isValidObject(options.tips) ) options.tips = {};
		//是否自定义 指定字段 tips
		if( !_isValidObject(options.tips[field]) ) options.tips[field] = {};
		//规则替换正则
		var reg = new RegExp('\{'+expVar+'\}','gi');
		options.tips[field][rule] = options.tips[field][rule] === undefined ? _tips[rule].replace(reg,expVal) : options.tips[field][rule].replace(reg,expVal); 
	}
	
	//检测待验证字段值的有效性 是否需要验证
	function _isInvalid(obj){
		
		//如果文本类型值为空，直接返回true
		if( $(obj).is(':text,:hidden,:file,:password,textarea') &&  $(obj).val().length==0 ){
			return false;	
		}else if( $(obj).is(':radio,:checkbox') && $(obj).filter(':checked').length==0 ){
		//如果是 radio/checkbox 没有任何选中项 直接返回 true
			return false;
		}else if( $(obj).is('select') && $(obj).find("option:selected").length==0 ){
		//如果是 select 没有任何选中项 直接返回 true
			return false;
		}
		return true;
	}
	
	//远程ajax验证缓存 当此字段值和上次验证通过后的值相同则 一定时间内不再重复请远端请求验证。
	function _checkAjaxCache(obj,cacheTime){
		var field = $(obj).data(namespace+'ajax_field') || {},
			cacheTime = parseInt(cacheTime) || 100;
		//
		if( $.type(field.value) === 'undefined' || field.value != $(obj).serialize() ){
			return false;
		}
		//判断是否超过缓存时间
        return field.time + cacheTime > _timestamp();

	}
	
	//获取当前时间戳
	function _timestamp(){
		return Math.round(new Date().getTime()/1000);	
	}
	
	//

	
	//验证规则集合
	var _rule = {
		
		/**
		 *	自定义回调规则
		 *  eg :  callback:function(){ }
		 *  适用字段 所有字段
		 */ 		
		callback:function(cb){
			
			//检测字段有效性 如 空值、没有选中项等不需要验证的 直接return true
			if(!_isInvalid(this)) return true;
			
			var result = cb.call(this);
			
			if(result===true){
				 return true;	
			}else{
				_tips.callback = result;
				return false;
			}
		},
		
		/**
		 *	正则表达式规则
		 *  eg :  regexp:[/^[a-z]\w+$/i, '不匹配提示信息']
		 *  适用字段 input[type=text],input[type=password],input[type=hidden],textarea
		 */ 
		regexp:function(val){
			
			if( $(this).is(':text,:hidden,:password,textarea') &&  $(this).val().length>0 ){
				
				if($.type(val)==='array' && $.type(val[0])==='regexp'){
					
					if( val[0].test($(this).val()) ){
						return true;	
					}
					_setSpecialExpTips($(this).attr('name'),'regexp','regexp',val[1]);
					return false;
							
				}else if(_isValidObject(val) && $.type(val.pattern)==='regexp'){
					
					if( val.pattern.test($(this).val()) ){
						return true;	
					}
					_setSpecialExpTips($(this).attr('name'),'regexp','regexp',val.tips);
					return false;					
				}
			}
			
			return true;
			
		},			
		
		/**
		 *	远程验证规则
		 *  eg :  ajax:'verify.php' ajax:['verify.php','get']
		 *  适用字段所有字段
		 */ 
		ajax:function(val){
			
			if(!_isInvalid(this)) return true;

			var s = {},
				//默认ajax验证缓存时间
				cache = 300,
				//验证结果
				result = true;			
				s.data  	= $(this).serialize();
				s.async 	= false;
				s.cache 	= false;
				s.context 	= this;
				s.dataType 	= 'text';
				
			switch($.type(val)){
				case 'string' : 
					 s.url  = val; 
					 s.type = 'get';
					 break;
				case 'array' : 
					 s.url  = val[0]; 
					 cache  = val[1] || cache;
					 s.type = val[2] || 'post';
					 break;
				case 'object' : 
					if(_isValidObject(val)){
					 	s.url  = val.url; 
					 	cache  = val.cache || cache;
						s.type = val.type  || 'post';
					}
					break;					 
			}
			
			s.success = function(response){
				if(response=='true'){
					//通过验证把当前通过的值写入缓存并且记录缓存时间
					$(this).data(namespace+'ajax_field', {value:s.data,time:_timestamp()} );
				}else{
					result = false;
					_setSpecialExpTips($(this).attr('name'),'ajax','ajax',response);						
				}
			};
			

			//远程ajax验证缓存 当此字段值和上次验证通过后的值相同则 一定时间内不在重复 请远端请求验证
			if( _checkAjaxCache(this,cache) ) return true;			
			
			$.ajax(s);
			
			return result;
		},		
		
		/**
		 *	必填性规则
		 *  eg :  required:true
		 *  适用字段 input[type=text],input[type=password],textarea 
		 */ 
		required:function(){
	
			if( $(this).is(':text,:password,textarea,input[type=number],input[type=email],input[type=url],input[type=search],input[type=tel]') ){
                return $(this).val().length>0;
			}
			
			//其他 字段 不判断 直接返回 true
			return true;
			
		},
		
		/**
		 *	相等规则
		 *  eg :  eqto:'password'    eqto:'#password'  eqto:$('#password')
		 *  适用字段 input[type=text],input[type=password],textarea 
		 */ 
		eqto:function(val){
	
			if( $(this).is(':text,:password,textarea,input[type=number],input[type=email],input[type=url],input[type=search],input[type=tel]') && $(this).val().length>0 ){
				
				//被相等对象
				var eqo;
				//字符串选择符
				if(_isValidString(val)){
					eqo = $.inArray(val.substr(0,1),['.','#'])===-1 ? $(":input[name='"+val+"']",$(this).parents('form')) : eqo = $(val);
				}else if( $.type(val) === 'object' ){
				//DOM或jQuery 对象 选择符
					eqo = $(val);
				}else{
					return true;	
				}
				
				if( $(eqo).length==0 ) return true;
				
				//
				if( $(this).val() == $(eqo).val() ) return true;

				_setSpecialExpTips( $(this).attr('name'),'eqto' , 'eqto', $(eqo).attr('title') || $(eqo).attr('name') );
				
				return false;

				
			}
			
			//其他 字段 不判断 直接返回 true
			return true;
			
		},
		
		/**
		 *	必选性规则
		 *  eg :  checked:true
		 *  适用字段 input[type=radio],input[type=checkbox] 
		 */ 
		checked:function(){
	
		
			if ($(this).is(':radio,:checkbox')) {

				return $(this).filter(":checked").length>0;
							
			}
			
			//其他 字段 不判断 直接返回 true
			return true;
			

		},
		
		/**
		 *	必选性规则
		 *  eg :  selected:true
		 *  适用字段 select 
		 */ 
		selected:function(val){
	
			if( $(this).is('select') ){
				var exclude = $.type(val)==='array' ? val : [val];
				return $(this).find("option:selected").length && $.inArray($(this).val().toString(),exclude)===-1;
			}
			
			//其他 字段 不判断 直接返回 true
			return true;

		},	
		
		/**
		 *	必上传规则
		 *  eg :  uploaded:true
		 *  适用字段 input[type=file] 
		 */ 
		uploaded:function(){
			
			var accept = true;
			$(this).each(function() {
            	if(!$(this).is(':file')) return true;
				if($(this).val().length==0){
					accept = false;
					return false;	
				}
            });
			
			return accept;

		},				
		
		/**
		 *	字符长度范围规则
		 *  eg   length:[5,10] or length:'5,10'
		 *  适用字段 input[type=text],input[type=password],textarea 
		 */ 
		length:function(val){
			
			// 排除非 text password textarea 的字段 或是值为空的字段
			if( !$(this).is(':text,:password,textarea,input[type=number],input[type=email],input[type=url],input[type=search],input[type=tel]') || $(this).val().length==0 ) return true;
			var min,max,arr = _castToArray(val);
			min = $.isNumeric(arr[0]) ? arr[0] : 0;
			max = $.isNumeric(arr[1]) ? arr[1] : 0;
			
			var len = $(this).val().length;
			
			if(max==0 && len>=min){
				return true;
			}else if( len >= min && len <= max  ){
				return true;
			}
			
			return false;
		},

		
		/**
		 *	字符最小长度规则
		 *  eg   minlength:5
		 *  适用字段 input[type=text],input[type=password],textarea 
		 */ 
		minlength:function(val){
			
			if( !$(this).is(':text,:password,textarea,input[type=number],input[type=email],input[type=url],input[type=search],input[type=tel]') || $(this).val().length==0 ) return true;
			
			var len = $(this).val().length,
				min = $.isNumeric(val) ? val : 0;
						
			return len >= min;

		},	
		
		/**
		 *	字符最大长度规则
		 *  eg   maxlength:5
		 *  适用字段 input[type=text],input[type=password],textarea 
		 */ 
		maxlength:function(val){
			
			if( !$(this).is(':text,:password,textarea,input[type=number],input[type=email],input[type=url],input[type=search],input[type=tel]') || $(this).val().length==0 ) return true;
			
			var len = $(this).val().length,
				max = $.isNumeric(val) ? val : 0;
						
			return max===0 ? true : (len <= max);

		},
		
		/**
		 *	数字范围规则
		 *  eg   length:[5,10] or length:'5,10'
		 *  适用字段 input[type=text],input[type=password],textarea 
		 */ 
		range:function(val){
			
			// 排除非 text password textarea 的字段 或是值为空的字段
			if( !$(this).is(':text,:password,textarea,input[type=number]') || $(this).val().length==0 ) return true;
			var min,max,
				arr = _castToArray(val),
				num = $(this).val();
			if( !$.isNumeric(num) ) return false;
			min = $.isNumeric(arr[0]) ? arr[0] : 0;
			max = $.isNumeric(arr[1]) ? arr[1] : 0;
			
		    return num >= min && num <= max;
        },
		
		/**
		 *	最小数字规则
		 *  eg   minrange:5
		 *  适用字段 input[type=text],input[type=password],textarea 
		 */ 
		minrange:function(val){
			
			if( !$(this).is(':text,:password,textarea,input[type=number]') || $(this).val().length==0 ) return true;
			
			var num = $(this).val(),
				min = $.isNumeric(val) ? val : 0;
						
			return  $.isNumeric(num) ?  (num >= min) : false;

		},	
		
		/**
		 *	最大数字规则
		 *  eg   maxrange:12
		 *  适用字段 input[type=text],input[type=password],textarea 
		 */ 
		maxrange:function(val){
			
			if( !$(this).is(':text,:password,textarea,input[type=number]') || $(this).val().length==0 ) return true;
			
			var num = $(this).val(),
				max = $.isNumeric(val) ? val : 0;
						
			return $.isNumeric(num) ? (num <= max) : false;

		},			
		
		
		/**
		 *	最小选择规则
		 *  eg   minchecked:1
		 *  适用字段 input[type=radio],input[type=checkbox]
		 */ 
		minchecked:function(val){
			
			if( $(this).is(':radio,:checkbox') ){
				
				var len = $(this).filter(":checked").length,
					min = $.isNumeric(val) ? val : 0;
							
				return len >= min;
			}
			
			return true;
		},	
		
		/**
		 *	最多选择规则
		 *  eg   maxchecked:6
		 *  适用字段 input[type=radio],input[type=checkbox]
		 */ 
		maxchecked:function(val){
			
			if( $(this).is(':radio,:checkbox') ){
				
				var len = $(this).filter(":checked").length,
					max = $.isNumeric(val) ? val : 0;
							
				return len <= max;
			}
			
			return true;
		},
		
		/**
		 *	最小选择规则
		 *  eg   minselected:1
		 *  适用字段 select
		 */ 
		minselected:function(val){
			
			if( $(this).is('select') ){
				
				var len = $(this).find("option:selected").length,
					min = $.isNumeric(val) ? val : 0;
							
				return len >= min;
			}
			
			return true;
		},	
		
		/**
		 *	最多选择规则
		 *  eg   maxchecked:6
		 *  适用字段 select
		 */ 
		maxselected:function(val){
			
			if( $(this).is('select') ){
				
				var len = $(this).find("option:selected").length,
					max = $.isNumeric(val) ? val : 0;
							
				return len <= max;
			}
			
			return true;
		},
		
		/**
		 *	上传文件后缀名限定规则
		 *  eg :  ext:['jpg','png'],'jpg,png'
		 *  适用字段 input[type=file]
		 */ 
		ext:function(val){
			//允许的扩展名
			var	exts   = _castToArray(val),
				accept = true;
			$(this).each(function() {
				//如果不是:file获取没有选中文件 跳出当前循环
				if( !$(this).is(':file') || $(this).val().length==0) return true;
				//获取当前文件扩展名
				var fileExt = $(this).val().split('.',2).pop();
				//检测扩展名是否在允许范围内
				if( $.inArray(fileExt,_castToArray(val)) === -1 ){
					accept = false;
					return false;
				}
				
			});
			
			if(accept===false){
				var field = $(this).attr('name');
				_setSpecialExpTips( field , 'ext' , 'multi',  $(this).filter(':file').length>1 ? '中有文件' : ''  );
				_setSpecialExpTips( field , 'ext' , 'ext',exts.join()  );
			}
			
			return accept;
			
		},											

		
		/**
		 *	必须是合法的email
		 *  eg  email:true
		 *  适用字段 input[type=text],textarea 
		 */ 		
		email:function(){
			
			if( !$(this).is(':text,textarea,input[type=email]') || $(this).val().length==0 ) return true;
			
			return /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test($(this).val());
				
		},
		
		/**
		 *	必须是合法的电话号码 [区号]+号码+[分机号]
		 *  eg  telephone:true
		 *  适用字段 input[type=text],textarea 
		 */ 		
		telephone:function(){
			
			if( !$(this).is(':text,textarea') || $(this).val().length==0 ) return true;
			
			return /^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/.test($(this).val());
				
		},	
		/**
		 *	必须是合法的手机号码
		 *  eg  cellphone:true
		 *  适用字段 input[type=text],textarea 
		 */ 		
		cellphone:function(){
			
			if( !$(this).is(':text,textarea,input[type=number],input[type=tel]') || $(this).val().length==0 ) return true;
			
			return /^1\d{10}$/.test($(this).val());
				
		},			
		
		/**
		 *	必须是合法的url
		 *  eg  url:true
		 *  适用字段 input[type=text],textarea 
		 */ 		
		url:function(){
			
			if( !$(this).is(':text,textarea,input[type=url]') || $(this).val().length==0 ) return true;
			
			return /^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"])*$/i.test($(this).val());
				
		},
		
		/**
		 *	必须是合法的QQ号码
		 *  eg  qq:true
		 *  适用字段 input[type=text],textarea 
		 */ 		
		qq:function(){
			
			if( !$(this).is(':text,textarea,input[type=number]') || $(this).val().length==0 ) return true;
			
			return /^[1-9]\d{4,10}$/.test($(this).val());
				
		},
		
		/**
		 *	必须是合法的身份证号码
		 *  eg  idcard:true
		 *  适用字段 input[type=text],textarea 
		 */ 		
		idcard:function(){
			
			if( !$(this).is(':text,textarea,input[type=number]') || $(this).val().length==0 ) return true;
			
			return /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/.test($(this).val());
				
		},		
		
		/**
		 *	必须是合法的邮政编码
		 *  eg  zip:true
		 *  适用字段 input[type=text],textarea 
		 */ 		
		zip:function(){
			
			if( !$(this).is(':text,textarea,input[type=number]') || $(this).val().length==0 ) return true;
			
			return /^[1-9]\d{5}$/.test($(this).val());
				
		},
		
		/**
		 *	必须是合法的货币金额
		 *  eg  currency:true
		 *  适用字段 input[type=text],textarea 
		 */ 		
		currency:function(){
			
			if( !$(this).is(':text,textarea,input[type=number]') || $(this).val().length==0 ) return true;
			
			return /^\d+(\.\d+)?$/.test($(this).val());
				
		},				
		
		/**
		 *	必须是合法日期格式
		 *  eg  date:true
		 *  适用字段 input[type=text],textarea 
		 */ 		

		date:function(){
			
			if( !$(this).is(':text,textarea,input[type=date]') || $(this).val().length==0 ) return true;
			
			return /^\d{4}\-\d{1,2}\-\d{1,2}$/.test($(this).val());
				
		},
		
		/**
		 *	必须是合法时间格式
		 *  eg  time:true
		 *  适用字段 input[type=text],textarea 
		 */ 		

		time:function(){
			
			if( !$(this).is(':text,textarea,input[type=time]') || $(this).val().length==0 ) return true;
			
			return /^\d{1,2}:\d{1,2}(:\d{1,2})?$/.test($(this).val());
				
		},
		
		/**
		 *	必须是合法日期时间格式
		 *  eg  datetime:true
		 *  适用字段 input[type=text],textarea 
		 */ 		

		datetime:function(){
			
			if( !$(this).is(':text,textarea,input[type=datatime],input[type=datetime-local]') || $(this).val().length==0 ) return true;
			
			return /^\d{4}\-\d{1,2}\-\d{1,2}\s\d{1,2}:\d{1,2}(:\d{1,2})?$/.test($(this).val());
				
		},										
		
		/**
		 *	必须是数字字符
		 *  eg  number:true
		 *  适用字段 input[type=text],input[type=password],textarea 
		 */ 		

		number:function(){
			
			if( !$(this).is(':text,:password,textarea,input[type=number]') || $(this).val().length==0 ) return true;
			
			return /^\d+$/.test($(this).val());
				
		},
				
		/**
		 *	必须是数字(十进制、八进制、十六进制)
		 *  eg  numeric:true
		 *  适用字段 input[type=text],input[type=password],textarea 
		 */ 		
		numeric:function(){
			
			if( !$(this).is(':text,:password,textarea,input[type=number]') || $(this).val().length==0 ) return true;
			
			return $.isNumeric($(this).val());
				
		},
		
		
		/**
		 *	必须是整数(包括负数)
		 *  eg  integer:true
		 *  适用字段 input[type=text],input[type=password],textarea 
		 */ 		
		integer:function(){
			
			if( !$(this).is(':text,:password,textarea,input[type=number]') || $(this).val().length==0 ) return true;
			
			return /^[-\+]?\d+$/.test($(this).val());
				
		},
		
		/**
		 *	必须是正整数
		 *  eg  ptint:true
		 *  适用字段 input[type=text],input[type=password],textarea 
		 */ 		
		ptint:function(){
			
			if( !$(this).is(':text,:password,textarea,input[type=number]') || $(this).val().length==0 ) return true;
			
			return /^\d+$/.test($(this).val());
				
		},
		
		/**
		 *	必须是负整数
		 *  eg  ntint:true
		 *  适用字段 input[type=text],input[type=password],textarea 
		 */ 		
		ntint:function(){
			
			if( !$(this).is(':text,:password,textarea,input[type=number]') || $(this).val().length==0 ) return true;
			
			return /^\-\d+$/.test($(this).val());
				
		},
						
		/**
		 *	必须是数字(包括整数、小数、负数)
		 *  eg  decimal:true
		 *  适用字段 input[type=text],input[type=password],textarea 
		 */ 		
		decimal:function(val){
			
			if( !$(this).is(':text,:password,textarea,input[type=number]') || $(this).val().length==0 ) return true;
			
			return /^[-\+]?\d+(\.\d+)?$/.test($(this).val());
				
		},														

		/**
		 *	必须是英文字母规则
		 *  eg  english:true
		 *  适用字段 input[type=text],input[type=password],textarea 
		 */ 		
		english:function(val){
			
			if( !$(this).is(':text,:password,textarea') || $(this).val().length==0 ) return true;
			
			return /^[A-Za-z]+$/.test($(this).val());
				
		},		
		/**
		 *	必须是汉字规则
		 *  eg  chinese:true
		 *  适用字段 input[type=text],textarea 
		 */ 		
		chinese:function(val){
			
			if( !$(this).is(':text,textarea') || $(this).val().length==0 ) return true;
			
			return /^[\u4e00-\u9fa5]+$/.test($(this).val());
				
		}
		
			
		
	};
	
	//规则预设提示信息
	var _tips = {
		
		callback	:	'{field}不正确',
		regexp		:	'{regexp}',
		ajax		:	'{ajax}',
		required	:	'{field}不能为空',
		eqto		:	'{field}和{eqto}不一致',
		checked		:	'{field}必须选择',
		selected	:	'{field}必须选择',
		uploaded	:	'{field}必须上传',
		length		: 	'{field}长度在{0}至{1}字符之间',
		minlength	: 	'{field}不能少于{0}个字符',
		maxlength	: 	'{field}不能超过{0}个字符',
		range		: 	'{field}必须在{0}到{1}之间的合法数字',
		minrange	: 	'{field}必须是不小于{0}的合法数字',
		maxrange	: 	'{field}必须是不大于{0}的合法数字',
		minchecked	: 	'{field}至少选择{0}个',
		maxchecked	: 	'{field}最多选择{0}个',
		minselected	: 	'{field}至少选择{0}个',
		maxselected	: 	'{field}最多选择{0}个',
		ext			: 	'{field}文件{multi}扩展名不在允许范围内[{ext}]',
		email		:	'{field}必须是合法的email地址',
		telephone	:	'{field}必须是合法的电话号码',
		cellphone	:	'{field}必须是合法的手机号码', 		
		url			:	'{field}必须是合法的URL地址',
		qq			:	'{field}必须是合法QQ号码',
		idcard		:	'{field}必须是合法身份证号码',
		zip			:	'{field}必须是合法邮政编码',
		currency	:	'{field}必须是货币金额',
		date		:	'{field}必须是合法日期格式 如：2014-05-28',
		time		:	'{field}必须是合法时间格式 如：23:00:00',
		datetime	:	'{field}必须是合法日期时间格式 如：2014-05-28 23:00:00',
		number		:	'{field}必须是数字字符',
		numeric		:	'{field}必须是数字',
		integer		:	'{field}必须是整数数字',
		ptint		:	'{field}必须是正整数',	
		ntint		:	'{field}必须是负整数',		
		decimal		:	'{field}必须是有效数字',
		english		:	'{field}必须填写英文字母',
		chinese		:	'{field}必须填写汉字'
	};
	
	
})(jQuery);

