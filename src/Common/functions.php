<?php
/**
 * Created by One admin@zcyso.com.
 * User: 周春宇
 * Date: 2018/1/3
 * Time: 上午10:38
 */

function AjaxReturn($data=[],$msg='',$code=200,$num=0){
    DI()->response->setRet($code+$num);
    DI()->response->setData($data);
    DI()->response->setMsg($msg);
    return DI()->response->output();
}

/**
 * 判断是否SSL协议
 * @return boolean
 */
function is_ssl() {
    if(isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))){
        return true;
    }elseif(isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'] )) {
        return true;
    }
    return false;
}

// 不区分大小写的in_array实现
function in_array_case($value,$array){
    return in_array(strtolower($value),array_map('strtolower',$array));
}

/**
 * 获取输入参数 支持过滤和默认值
 * 使用方法:
 * <code>
 * I('id',0); 获取id参数 自动判断get或者post
 * I('post.name','','htmlspecialchars'); 获取$_POST['name']
 * I('get.'); 获取$_GET
 * </code>
 * @param string $name 变量的名称 支持指定类型
 * @param mixed $default 不存在的时候默认值
 * @param mixed $filter 参数过滤方法
 * @param mixed $datas 要获取的额外数据源
 * @return mixed
 */
function I($name,$default='',$filter=null,$datas=null) {
    static $_PUT	=	null;
    if(strpos($name,'/')){ // 指定修饰符
        list($name,$type) 	=	explode('/',$name,2);
    }else{ // 默认强制转换为字符串
        $type   =   's';
    }
    if(strpos($name,'.')) { // 指定参数来源
        list($method,$name) =   explode('.',$name,2);
    }else{ // 默认为自动判断
        $method =   'param';
    }
    switch(strtolower($method)) {
        case 'get'     :
            $input =& $_GET;
            break;
        case 'post'    :
            $input =& $_POST;
            break;
        case 'put'     :
            if(is_null($_PUT)){
                parse_str(file_get_contents('php://input'), $_PUT);
            }
            $input 	=	$_PUT;
            break;
        case 'param'   :
            switch($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    $input  =  $_POST;
                    break;
                case 'PUT':
                    if(is_null($_PUT)){
                        parse_str(file_get_contents('php://input'), $_PUT);
                    }
                    $input 	=	$_PUT;
                    break;
                default:
                    $input  =  $_GET;
            }
            break;
        case 'path'    :
            $input  =   array();
            if(!empty($_SERVER['PATH_INFO'])){
                $depr   =   C('URL_PATHINFO_DEPR');
                $input  =   explode($depr,trim($_SERVER['PATH_INFO'],$depr));
            }
            break;
        case 'request' :
            $input =& $_REQUEST;
            break;
        case 'session' :
            $input =& $_SESSION;
            break;
        case 'cookie'  :
            $input =& $_COOKIE;
            break;
        case 'server'  :
            $input =& $_SERVER;
            break;
        case 'globals' :
            $input =& $GLOBALS;
            break;
        case 'data'    :
            $input =& $datas;
            break;
        default:
            return null;
    }
    if(''==$name) { // 获取全部变量
        $data       =   $input;
        $filters    =   isset($filter)?$filter:C('DEFAULT_FILTER');
        if($filters) {
            if(is_string($filters)){
                $filters    =   explode(',',$filters);
            }
            foreach($filters as $filter){
                $data   =   array_map_recursive($filter,$data); // 参数过滤
            }
        }
    }elseif(isset($input[$name])) { // 取值操作
        $data       =   $input[$name];
        $filters    =   isset($filter)?$filter:C('DEFAULT_FILTER');
        if($filters) {
            if(is_string($filters)){
                if(0 === strpos($filters,'/')){
                    if(1 !== preg_match($filters,(string)$data)){
                        // 支持正则验证
                        return   isset($default) ? $default : null;
                    }
                }else{
                    $filters    =   explode(',',$filters);
                }
            }elseif(is_int($filters)){
                $filters    =   array($filters);
            }

            if(is_array($filters)){
                foreach($filters as $filter){
                    if(function_exists($filter)) {
                        $data   =   is_array($data) ? array_map_recursive($filter,$data) : $filter($data); // 参数过滤
                    }else{
                        $data   =   filter_var($data,is_int($filter) ? $filter : filter_id($filter));
                        if(false === $data) {
                            return   isset($default) ? $default : null;
                        }
                    }
                }
            }
        }
        if(!empty($type)){
            switch(strtolower($type)){
                case 'a':	// 数组
                    $data 	=	(array)$data;
                    break;
                case 'd':	// 数字
                    $data 	=	(int)$data;
                    break;
                case 'f':	// 浮点
                    $data 	=	(float)$data;
                    break;
                case 'b':	// 布尔
                    $data 	=	(boolean)$data;
                    break;
                case 's':   // 字符串
                default:
                    $data   =   (string)$data;
            }
        }
    }else{ // 变量默认值
        $data       =    isset($default)?$default:null;
    }
    is_array($data) && array_walk_recursive($data,'think_filter');
    return $data;
}


/**
 * 获取和设置配置参数 支持批量定义
 * @param string|array $name 配置变量
 * @param mixed $default 默认值
 * @return mixed
 */
function C($name=null,$default=null) {
    if(!strpos($name, '.')){
        $app_conf = include __ROOT__.'config/app.php';
        $dbs_conf = include __ROOT__.'config/dbs.php';
        $sys_conf = include __ROOT__.'config/sys.php';
        $_config = array_merge($app_conf,$dbs_conf,$sys_conf);

        if(!$name){
            return $_config;
        }
        return isset($_config[$name]) ? $_config[$name] : null;
    }
    return \PhalApi\DI()->config->get($name,$default);
}

/*
 * 获取请求IP
 */
function getClientIp(){
    return \PhalApi\Tool::getClientIp();
}

/*
 * 获取随机字符串
 * type: 0：随机字符串，1：数字，2：a-zA-Z，3：包含特殊字符的字符串
 * len: 字符串长度
 */
function RandStr($length=6,$type=0){
    $arr = array(1 => "0123456789", 2 => "abcdefghijklmnopqrstuvwxyz", 3 => "ABCDEFGHIJKLMNOPQRSTUVWXYZ", 4 => "~@#$%^&*(){}[]|");
    $code = '';
    if ($type == 0) {
        array_pop($arr);
        $string = implode("", $arr);
    } else if ($type == "-1") {
        $string = implode("", $arr);
    } else {
        $string = $arr[$type];
    }
    $count = strlen($string) - 1;
    for ($i = 0; $i < $length; $i++) {
        $str[$i] = $string[rand(0, $count)];
        $code .= $str[$i];
    }
    return $code;
}

/**
 * 去除字符串空格和回车
 *
 * @param  string $str 待处理字符串
 *
 * @return string
 */
function trimStr($str){
    return \PhalApi\Tool::trimSpaceInStr($str);
}

/**
 * 获取数组value值不存在时返回默认值
 * 不建议在大循环中使用会有效率问题
 *
 * @param array      $arr     数组实例
 * @param string|int $key     数据key值
 * @param string     $default 默认值
 *
 * @return string
 */
function arrIndex($arr, $key, $default = '') {
    return \PhalApi\Tool::arrIndex($arr,$key,$default);
}

/**
 * 根据路径创建目录或文件
 *
 * @param string $path 需要创建目录路径
 *
 * @throws PhalApi_Exception_BadRequest
 */
function createDir($path) {
    return \PhalApi\Tool::createDir($path);
}

/**
 * 删除目录以及子目录等所有文件
 *
 * - 请注意不要删除重要目录！
 *
 * @param string $path 需要删除目录路径
 */
function deleteDir($path) {
    return \PhalApi\Tool::deleteDir($path);
}

/**
 * 数组转XML格式
 *
 * @param array $arr 数组
 * @param string $root 根节点名称
 * @param int $num 回调次数
 *
 * @return string xml
 */
function arrayToXml($arr, $root='xml', $num=0){
    return \PhalApi\Tool::arrayToXml($arr,$root,$num);
}
/**
 * XML格式转数组
 *
 * @param  string $xml
 *
 * @return mixed|array
 */
function xmlToArray($xml){
    return \PhalApi\Tool::xmlToArray($xml);
}