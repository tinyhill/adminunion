<?php

/**
 * 使用 etag 标记控制缓存
 */
set_expire(date('Ymd'));

/**
 * 获取待查询域名
 */
$d = get_parameter('domain');
$d = $d ? get_domain($d) : 'adminunion.com';

/**
 * 获取当前子域名
 */
$host = get_host();

/**
 * 根据参数载入视图
 */
$filepath = APPPATH . 'view/' . $host . '.php';

if (file_exists($filepath)) {

	require_once ($filepath);

} else {

	$host = 'www';
	require_once (APPPATH . 'view/www.php');

}

?>