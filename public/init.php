<?php
/**
 * 统一初始化
 */

// 定义项目路径
defined('API_ROOT') || define('API_ROOT', dirname(__FILE__) . '/../');


// 引入composer
require_once API_ROOT . '/vendor/autoload.php';

// 时区设置
date_default_timezone_set('Asia/Shanghai');

// 引入DI服务
include API_ROOT . '/config/di.php';

// 调试模式
if (\PhalApi\DI()->debug) {
    // 启动追踪器
    \PhalApi\DI()->tracer->mark();

    error_reporting(E_ALL);
    ini_set('display_errors', 'On'); 
}

// 翻译语言包设定
\PhalApi\SL('zh_cn');


defined('__APP__') || define('__APP__',str_replace('\\','/',realpath(dirname(__FILE__).'/'))."/");
defined('__ROOT__') || define('__ROOT__',str_replace('\\','/',realpath(dirname(__FILE__).'/'))."/../");
$is_ssl = is_ssl()? 'https://' : 'http://';
defined('Domain') || define('Domain',$is_ssl.$_SERVER['HTTP_HOST']);
