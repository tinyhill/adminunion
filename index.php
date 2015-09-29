<?php

/**
 * 禁止输出错误报告
 */
error_reporting(0);

/**
 * 定义根绝对路径
 */
define('APPPATH', dirname(__FILE__) . '/application/');

/**
 * 载入通用函数库
 */
require_once (APPPATH . 'core/common.php');

/**
 * 载入核心路由配置
 */
require_once (APPPATH . 'core/router.php');