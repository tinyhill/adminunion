<?php

/**
 * 禁止输出错误报告
 */
error_reporting(0);

/**
 * 定义应用绝对路径
 */
define('APPPATH', dirname(__FILE__) . '/application/');

/**
 * 载入通用函数库
 */
require_once (APPPATH . 'core/common.php');

/**
 * 使用 etag 标记控制缓存
 */
set_expire(date('Ymd'));

/**
 * 查询挂件路由映射
 */
$filepath = APPPATH . 'controller/' . get_host() . '/widget.php';

if (file_exists($filepath)) {

	require_once ($filepath);

} else {
	echo 'Directory access is forbidden.';
}

?>