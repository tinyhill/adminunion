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

header('content-type:text/plain;charset=utf-8');

/**
 * 获取控制器、方法、域名参数
 */
$c = get_parameter('c');
$m = get_parameter('m');
$d = get_parameter('d');

if ($c && $m && $d) {

	/**
	 * 载入控制器规则
	 */
	require_once (APPPATH . 'core/controller.php');

	$controller = $c . '_controller';
	echo $controller();

} else {
	echo 'Directory access is forbidden.';
}

?>