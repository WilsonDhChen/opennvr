<?php
header('Content-Type:text/html;charset=utf-8');
//开启 调试
define('APP_DEBUG', true);

//项目名称
define('APP_NAME','Oadmin');
//WEB服务器根路径
define('APP_ROOT','.');
//项目路径
define('APP_PATH', APP_ROOT.'/oadmin/');
//用户上传文件存储根路径
define('APP_UFS',APP_ROOT.'/ufs');
//UFS URL 路径
$ufs_url = dirname(dirname($_SERVER['SCRIPT_NAME']));
$ufs_url = ($ufs_url==DIRECTORY_SEPARATOR) ? '':$ufs_url;
define('UFS_URL',$ufs_url.'/ufs');
//当前APP的URL路径
define('DIR_URL',str_replace('\\','/',rtrim(str_replace($_SERVER['DOCUMENT_ROOT'],'',dirname($_SERVER['SCRIPT_FILENAME'])),'/')));
//运行驱动 ThinkPHP
require APP_ROOT.'/system/ThinkPHP.php';
