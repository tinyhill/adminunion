<?php

/**
 * 查询 Alexa 全球排名
 */
function get_alexa_rank ($d) {

	$content = @file_get_contents('http://data.alexa.com/data?cli=10&url=' . $d) or die('0');

	if (preg_match('/<POPULARITY URL=\"(.*?)\" TEXT=\"(.*?)\"/i', $content, $match)) {
		$alexa_rank = strip_tags($match[2]);
	} else {
		$alexa_rank = '0';
	}

	return $alexa_rank;

}

/**
 * 查询搜狗评级
 */
function get_sogou_rank ($d) {

	$content = @file_get_contents('http://rank.ie.sogou.com/sogourank.php?ur=http%3A%2F%2F' . $d . '%2F') or die('0');
	$sogou_rank = $content ? trim(preg_replace('/sogourank=(.*?)/', '', $content)) : '0';

	return $sogou_rank;

}

/**
 * 查询中国网站排行
 */
function get_china_rank ($d) {

	$content = @file_get_contents('http://www.chinarank.org.cn/overview/Info.do?url=' . $d) or die('0');
	$content = @iconv('gb2312', 'utf-8', $content);

	if (preg_match('/<span class=\"bold\">当前排名：<\/span><span class=\"rank_font_y2\">(.*?)<\/span>/i', $content, $match)) {
		$china_rank = $match[1];
	} else {
		$china_rank = '0';
	}

	return $china_rank;

}

/**
 * 查询百度快照
 */
function get_baidu_snapshot ($d) {

	$content = @file_get_contents('http://www.baidu.com/s?wd=' . $d) or die('查询失败');
	$content = @mb_convert_encoding($content, 'utf-8', 'gb2312');

	if (preg_match('/<span class=\"g\">(.*?)<b>' . $d . '<\/b>\/(.*?)<\/span>/i', $content, $match)) {
		$match_arr = explode(' ', trim($match[2]));
		$baidu_snapshot = array_pop($match_arr);
	} else {
		$baidu_snapshot = '<span style="font-size:12px;">没有记录</span>';
	}

	return $baidu_snapshot;

}

/**
 * 查询百度收录情况和反向链接
 */
function get_baidu_index ($d, $q) {

	if ($q == 'link')
		$q = 'domain';

	$content = get_content('http://www.baidu.com/s?wd=' . $q . '%3A' . $d) or die('0');
	//$content = @mb_convert_encoding($content, 'utf-8', 'gb2312');

	if (preg_match('/找到相关结果(.*?)个/i', $content, $match)) {
		$baidu_index = preg_replace('/[\x80-\xff,]/', '', $match[1]);
	} else {
		$baidu_index = '0';
	}

	return $baidu_index;

}

/**
 * 查询谷歌收录情况和反向链接
 */
function get_google_index ($d, $q) {

	$context = stream_context_create(array('http' => array('header' => 'Cookie: PREF=ID=fef74816681e7898:U=9ea73b7f54aa9005:FF=2:LD=zh-CN:NW=1:TM=1295952619:LM=1296005167:S=Dk6Hp_5SDKZ3OhJy;', )));
	$content = @file_get_contents('http://203.208.46.85/search?hl=zh-CN&q=' . $q . '%3A' . $d, false, $context) or die('0');
	$content = @iconv('gb2312', 'utf-8', $content);

	if ($q == 'site')
		$regex = '/找到约 (.*?) 条结果/i';
	if ($q == 'link')
		$regex = '/获得 (.*?) 条结果/i';

	if (preg_match($regex, $content, $match)) {
		$google_index = preg_replace('/[\x80-\xff,]/', '', $match[1]);
	} else {
		$google_index = '0';
	}

	return $google_index;

}

/**
 * 查询雅虎收录情况和反向链接
 */
function get_yahoo_index ($d, $q) {

	$content = @file_get_contents('http://www.yahoo.cn/s?q=' . $q . '%3A' . $d) or die('0');

	if (preg_match('/找到相关网页约(.*?)条/i', $content, $match)) {
		$yahoo_index = str_replace(',', '', $match[1]);
	} else {
		$yahoo_index = '0';
	}

	return $yahoo_index;

}

/**
 * 查询搜狗收录情况和反向链接
 */
function get_sogou_index ($d, $q) {

	$content = @file_get_contents('http://www.sogou.com/web?query=' . $q . '%3A' . $d) or die('0');
	$content = @iconv('gb2312', 'utf-8', $content);

	if (preg_match('/<!--resultbarnum:(.*?)-->/i', $content, $match)) {
		$sogou_index = str_replace(',', '', $match[1]);
	} else {
		$sogou_index = '0';
	}

	return $sogou_index;

}

/**
 * 查询搜搜收录情况和反向链接
 */
function get_soso_index ($d, $q) {

	$content = @file_get_contents('http://www.soso.com/q?w=' . $q . '%3A' . $d) or die('0');
	$content = @iconv('gb2312', 'utf-8', $content);

	if (preg_match('/搜索到约(.*?)项结果/i', $content, $match)) {
		$soso_index = str_replace(',', '', $match[1]);
	} else {
		$soso_index = '0';
	}

	return $soso_index;

}

/**
 * 查询有道收录情况和反向链接
 */
function get_youdao_index ($d, $q) {

	$content = @file_get_contents('http://www.youdao.com/search?q=' . $q . '%3A' . $d) or die('0');

	if (preg_match('/<span class=\"srd\">共(.*?)条结果<\/span>/i', $content, $match)) {
		$youdao_index = str_replace(',', '', str_replace('万', '', str_replace('约', '', $match[1])));
	} else {
		$youdao_index = '0';
	}

	return $youdao_index;

}

/**
 * 查询必应收录情况和反向链接
 */
function get_bing_index ($d, $q) {

	//$content = @file_get_contents('http://cn.bing.com/search?q=' . $q . '%3A' . $d) or die('0');
	require(APPPATH . 'lib/Snoopy.class.php');

	$snoopy = new Snoopy();
	$snoopy->agent = $_SERVER['HTTP_USER_AGENT'];
	$snoopy->referer = $_SERVER['HTTP_REFERER'];
	$snoopy->fetch('http://cn.bing.com/search?q=' . $q . '%3A' . $d);

	$content = $snoopy->results;

	if (preg_match('/共 (.*?) 条/i', $content, $match)) {
		$bing_index = str_replace(',', '', $match[1]);
	} else {
		$bing_index = '0';
	}

	return $bing_index;

}

/**
 * 查询百度近日收录情况
 */
function get_baidu_recent ($d, $t) {

	$content = get_content('http://www.baidu.com/s?wd=site%3A' . $d . '&lm=' . $t);
	//$content = @mb_convert_encoding($content, 'utf-8', 'gb2312');

	if (preg_match('/找到相关结果(.*?)个/i', $content, $match)) {
		$baidu_recent = preg_replace('/[\x80-\xff,]/', '', $match[1]);
	} else {
		$baidu_recent = '0';
	}

	return $baidu_recent;

}

/**
 * 查询谷歌近日收录情况
 */
function get_google_recent ($d, $t) {

	$context = stream_context_create(array('http' => array('header' => 'Cookie: PREF=ID=fef74816681e7898:U=9ea73b7f54aa9005:FF=2:LD=zh-CN:NW=1:TM=1295952619:LM=1296005167:S=Dk6Hp_5SDKZ3OhJy;', )));
	$content = @file_get_contents('http://203.208.46.85/search?hl=zh-CN&q=site%3A' . $d . '&as_qdr=' . $t, false, $context) or die('0');
	$content = @iconv('gb2312', 'utf-8', $content);

	if (preg_match('/找到约 (.*?) 条结果/i', $content, $match)) {
		$google_recent = preg_replace('/[\x80-\xff,]/', '', $match[1]);
	} else {
		$google_recent = '0';
	}

	return $google_recent;

}

?>