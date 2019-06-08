<?php
/**
 * 项目 主配置文件
 * @auther : Kin
 */
 
return array(
		
		/* 系统配置 */
		'APP_AUTOLOAD_PATH'  		=> 'ORG.Util,ORG.Net',	 	//自动加载ORG包中的常用工具Uitl包
		'URL_MODEL'          		=> 2,	  		 	//URL模式
		'TMPL_TEMPLATE_SUFFIX'		=> '.tpl.php',	  	//模版文件后缀
		'VAR_AJAX_SUBMIT'			=> 'isAjaxRequest',	//AJAX提交标识
	  	'URL_HTML_SUFFIX'			=> '',
		'URL_CASE_INSENSITIVE'		=> true,			//URL地址区分大小写设置(true 不区分大小写)
		'LOAD_EXT_FILE'  			=> 'function',		//自动加载项目公共项目函数
		'TMPL_ACTION_ERROR'     	=> TMPL_PATH.'/Base/response.tpl.php', // 默认错误跳转对应的模板文件
		'TMPL_ACTION_SUCCESS'   	=> TMPL_PATH.'/Base/response.tpl.php', // 默认成功跳转对应的模板文件
		'TMPL_EXCEPTION_FILE' 		=> TMPL_PATH.'/Base/exception.tpl.php',// 异常页面的模板文件
		'TMPL_STRIP_SPACE'			=> false,	  	
		'VAR_PAGE'  				=> 'page', 			//分页字段名称
		'TMPL_PARSE_STRING'  		=> array(
											'__DIR__'	=> DIR_URL,
											'__UFS__'	=> UFS_URL,
											'__STATIC__'=> DIR_URL.'/oadmin/static',
											//反斜杠替换符，模版中使用，防止模版引擎解析掉
											'__BACKSLASH__' => chr(92),
										),
		
		'SUPER_ADMIN_ROLE'			=> 1,      			//超级管理员用户组id
		'OAUI_DEFAULT_SKIN'			=> 'black',			//OAmin默认皮肤
		
		/* 数据库配置 */
		'DB_TYPE'				=> 'mysqli',				// 数据库类型
		'DB_HOST'               => '127.0.0.1', 			// 服务器地址
		'DB_NAME'               => 'nvr',    		// 数据库名
		'DB_PREFIX'				=> 'ly_',					// 表前缀
		'DB_USER'               => 'root',  			//用户名
		'DB_PWD'                => 'QWE123@#rty',  	// 密码
	

		'DB_CONFIG2' 			=> array(
									'db_type'  => 'mysql',
									'db_user'  => 'root',
									'db_pwd'   => 'QWE123@#rty',
									'db_host'  => '127.0.0.1',
									'db_name'  => 'nvr_default'
		),

		
);
