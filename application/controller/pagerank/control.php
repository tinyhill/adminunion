<?php

define('BASEPATH', dirname(__FILE__) . '/');

/**
 * 读取远程页面内容
 */
function get_url_content ($url) {

	require(APPPATH . 'lib/Snoopy.class.php');

	$snoopy = new Snoopy();
	$snoopy->agent = $_SERVER['HTTP_USER_AGENT'];
	$snoopy->referer = $_SERVER['HTTP_REFERER'];
	$snoopy->fetch($url);

	return $snoopy->results;

}

/**
 * 查询域名 Pagerank 值
 */
function get_pagerank ($d) {

	require_once (BASEPATH . 'Pagerank.class.php');

	$pagerank = new Pagerank();
	return $pagerank->getGPR($d);

}

/**
 * 验证 Pagerank 值真实性
 */
function get_validate ($d) {

	//抓取页面数据
	$context = stream_context_create(array('http' => array('method' => 'GET', 'timeout' => 10, 'header' => "Host: zh.wikipedia.org\r\n" . "Accept-language: zh-cn\r\n" . "User-Agent: mozilla/5.0 (windows; u; windows nt 5.1; zh-cn; rv:1.9.2.3) gecko/20100401 firefox/3.6.3" . "Accept: *//*")));

	$h = @file_get_contents('http://203.208.46.85/search?hl=zh-CN&q=info%3A' . $d, false, $context) or die('真实性未知');

	//筛选站点网址
	if (preg_match_all('/<cite>(.*?)<\/cite>/', $h, $match)) {

		$md = get_domain($match[1][0]);
		$sd = str_replace('www.', '', get_domain($d));

		//在匹配站点中查找
		if (stripos($md, $sd) === false) {

			return '劫持 ' . $md;

		} else {

			return '真实';

		}

	} else {

		return '真实';

	}

}

?>