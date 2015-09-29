<?php

/**
 * 引入核心接口缓存类
 */
require_once (APPPATH . 'lib/Cache.class.php');

/**
 * 定义 Alexa 模块控制器
 */
function alexa_controller ()
{
	$d = get_parameter('d');
	$m = get_parameter('m');

	//参数预处理
	$d = str_replace('www.', '', $d);

	//引入 Alexa 模块核心文件
	require_once(APPPATH . 'controller/alexa/control.php');

	//根据类型查询 Alexa 数据
	switch ($m) {
		case 'summary': {
			//return get_alexa_summary($d);
			return get_cache('get_alexa_summary', array($d), 3600 * 24 * 2);
			break;
		}
		case 'siteinfo': {
			//return get_alexa_siteinfo($d);
			return get_cache('get_alexa_siteinfo', array($d), 3600 * 24 * 2);
			break;
		}
	}
}

// ------------------------------------------------------------------------

/**
 * 定义 Icp 模块控制器
 */
function icp_controller ()
{
	$d = get_parameter('d');

	//参数预处理
	$d = str_replace('www.', '', $d);

	//引入 Icp 模块核心文件
	require_once(APPPATH . 'controller/icp/control.php');

	//return get_icp($d);
	return get_cache('get_icp', array($d), 3600 * 24 * 90);
}

// ------------------------------------------------------------------------

/**
 * 定义 Indexed 模块控制器
 */
function indexed_controller ()
{
	$m = get_parameter('m');
	$d = get_parameter('d');
	$q = get_parameter('q');
	$t = get_parameter('t');

	//引入 Indexed 模块核心文件
	require_once(APPPATH . 'controller/indexed/control.php');

	//根据类型查询排名、收录数据
	switch ($m) {
		case 'alexa_rank' : {
			//return get_alexa_rank($d);
			return get_cache('get_alexa_rank', array($d), 3600 * 24);
			break;
		}
		case 'sogou_rank' : {
			//return get_sogou_rank($d);
			return get_cache('get_sogou_rank', array($d), 3600 * 24);
			break;
		}
		case 'china_rank' : {
			//return get_china_rank($d);
			return get_cache('get_china_rank', array($d), 3600 * 24);
			break;
		}
		case 'baidu_snapshot' : {
			return get_cache('get_baidu_snapshot', array($d), 3600 * 24);
			break;
		}
		case 'baidu_index' : {
			return get_cache('get_baidu_index', array($d, $q), 3600 * 24);
			break;
		}
		case 'google_index' : {
			//return get_google_index($d, $q);
			return get_cache('get_google_index', array($d, $q), 3600 * 24);
			break;
		}
		case 'yahoo_index' : {
			//return get_yahoo_index($d, $q);
			return get_cache('get_yahoo_index', array($d, $q), 3600 * 24);
			break;
		}
		case 'sogou_index' : {
			//return get_sogou_index($d, $q);
			return get_cache('get_sogou_index', array($d, $q), 3600 * 24);
			break;
		}
		case 'soso_index' : {
			//return get_soso_index($d, $q);
			return get_cache('get_soso_index', array($d, $q), 3600 * 24);
			break;
		}
		case 'youdao_index' : {
			//return get_youdao_index($d, $q);
			return get_cache('get_youdao_index', array($d, $q), 3600 * 24);
			break;
		}
		case 'bing_index' : {
			//return get_bing_index($d, $q);
			return get_cache('get_bing_index', array($d, $q), 3600 * 24);
			break;
		}
		case 'baidu_recent' : {
			//return get_baidu_recent($d, $t);
			return get_cache('get_baidu_recent', array($d, $t), 3600 * 24);
			break;
		}
		case 'google_recent' : {
			//return get_google_recent($d, $t);
			return get_cache('get_google_recent', array($d, $t), 3600 * 24);
			break;
		}
	}
}

// ------------------------------------------------------------------------

/**
 * 定义 IP 模块控制器
 */

function ip_controller ()
{
	$m = get_parameter('m');
	$d = get_parameter('d');

	//引入 IP 模块核心文件
	require_once(APPPATH . 'controller/ip/control.php');

	//根据类型查询 IP 数据
	switch ($m) {
		case 'address': {
			return get_ip_address($d);
			break;
		}
		case 'geolocation': {
			return get_ip_geolocation($d);
			break;
		}
		case 'reverse': {
			//return get_ip_reverse($d);
			return get_cache('get_ip_reverse', array($d), 3600 * 24 * 7);
			break;
		}
	}
}

// ------------------------------------------------------------------------

/**
 * 定义 Keyword 模块控制器
 */
function keyword_controller ()
{
	$m = get_parameter('m');
	$d = get_parameter('d');
	$q = get_parameter('q');

	//引入 Keyword 模块核心文件
	require_once(APPPATH . 'controller/keyword/control.php');

	//根据类型查询排名、关键词、收录数据
	switch ($m) {
		case 'baidu_rank' : {
			//return get_baidu_rank($d, $q);
			return get_cache('get_baidu_rank', array($d, $q), 3600 * 24);
			break;
		}
		case 'google_rank' : {
			//return get_google_rank($d, $q);
			return get_cache('get_google_rank', array($d, $q), 3600 * 24);
			break;
		}
		case 'baidu_related' : {
			//return get_baidu_related($q);
			return get_cache('get_baidu_related', array($q), 3600 * 24);
			break;
		}
		case 'baidu_index' : {
			//return get_baidu_index($q);
			return get_cache('get_baidu_index', array($q), 3600 * 24);
			break;
		}
		case 'google_index' : {
			//return get_google_index($q);
			return get_cache('get_google_index', array($q), 3600 * 24);
			break;
		}
	}
}

// ------------------------------------------------------------------------

/**
 * 定义 Link 模块控制器
 */
function link_controller ()
{
	$m = get_parameter('m');
	$d = get_parameter('d');
	$site = get_parameter('site');

	//引入 Link 模块核心文件
	require_once(APPPATH . 'controller/link/control.php');

	//根据类型查询反链数据
	switch ($m) {
		case 'backlinks' : {
			//return get_backlinks($d);
			return get_cache('get_backlinks', array($d), 3600 * 24);
			break;
		}
		case 'is_backlinked' : {
			//return is_backlinked($site, $d);
			return get_cache('is_backlinked', array($site, $d), 3600 * 24);
			break;
		}
	}
}

// ------------------------------------------------------------------------

/**
 * 定义 Pagerank 模块控制器
 */
function pagerank_controller ()
{
	$m = get_parameter('m');
	$d = get_parameter('d');

	//参数预处理
	$d = str_replace('www.', '', $d);

	//引入 Pagerank 模块核心文件
	require_once(APPPATH . 'controller/pagerank/control.php');

	//根据类型查询排名数据
	switch ($m) {
		case 'pagerank' : {
			//return get_pagerank($d);
			return get_cache('get_pagerank', array($d), 3600 * 24 * 30);
			break;
		}
		case 'validate' : {
			//return get_validate($d);
			return get_cache('get_validate', array($d), 3600 * 24 * 30);
			break;
		}
	}
}

// ------------------------------------------------------------------------

/**
 * 定义 Ping 模块控制器
 */
function ping_controller ()
{
	$m = get_parameter('m');
	$d = get_parameter('d');

	//引入 Ping 模块核心文件
	require_once(APPPATH . 'controller/ping/control.php');

	//根据类型执行 Ping 通知
	switch ($m) {
		case 'baidu' : {
			return ping_baidu($d);
			break;
		}
		case 'google' : {
			return ping_google($d);
			break;
		}
		case 'yahoo' : {
			return ping_yahoo($d);
			break;
		}
		case 'youdao' : {
			return ping_youdao($d);
			break;
		}
	}
}

// ------------------------------------------------------------------------

/**
 * 定义 Spider 模块控制器
 */
function spider_controller ()
{
	$d = get_parameter('d');

	//引入 Spider 模块核心文件
	require_once(APPPATH . 'controller/spider/control.php');

	return get_spider($d);
}

// ------------------------------------------------------------------------

/**
 * 定义 Whois 模块控制器
 */
function whois_controller ()
{
	$m = get_parameter('m');
	$d = get_parameter('d');
	$tld = get_parameter('tld');

	//参数预处理
	$d = str_replace('www.', '', $d);

	//引入 Whois 模块核心文件
	require_once(APPPATH . 'controller/whois/control.php');

	//根据类型查询 Whois 信息
	switch ($m) {
		case 'whois' : {
			return get_whois($d);
			break;
		}
		case 'availability' : {
			return get_availability($d, $tld);
			break;
		}
	}
}

?>