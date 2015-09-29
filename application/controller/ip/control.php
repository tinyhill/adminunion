<?php

define('BASEPATH', dirname(__FILE__) . '/');

/**
 * 根据 IP 获得地理位置
 */
function get_ip_address ($d) {

	require_once (BASEPATH . 'QQWry/QQWry.php');

	//判断参数格式
	if (!preg_match('/^[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}$/', $d)) {
		$d = gethostbyname($d);
	}

	$QQWry = new QQWry;
	$ifErr = $QQWry -> QQWry($d);
	$location = $QQWry -> Country . ' ' . $QQWry -> Local;

	//判断中文地址编码
	if (!mb_check_encoding($location, 'utf-8')) {
		$location = mb_convert_encoding($location, 'utf-8', 'gbk');
	}

	return '["' . $d . '","' . $location . '"]';

}

/**
 * 根据 IP 加载谷歌地图
 */
function get_ip_geolocation ($d) {

	require_once ('geoip/geoipcity.inc');
	require_once ('geoip/geoipregionvars.php');

	$d = gethostbyname($d);
	$gi = geoip_open(BASEPATH . 'geoip/GeoLiteCity.dat', GEOIP_STANDARD);
	$r = GeoIP_record_by_addr($gi, $d);
	geoip_close($gi);

	return '["' . $r -> latitude . '","' . $r -> longitude . '"]';

}

/**
 * 根据 IP 反向查询站点
 */
function get_ip_reverse ($d) {

	$ip_address = gethostbyname($d);

	//从搜索引擎抓取数据
	$h1 = @file_get_contents('http://cn.bing.com/search?q=ip:' . $ip_address . '&count=59') or die('{"code":400}');
	$h2 = @file_get_contents('http://cn.bing.com/search?q=ip:' . $ip_address . '&count=59&first=60') or die('{"code":400}');
	$h = $h1 . $h2;

	//获得所有站点数据
	if (preg_match_all('/<div class="sb_meta"><cite>(.*?)<\/cite>/', $h, $match)) {

		//创建临时站点数组栈
		$site_arr = array();

		//站点数据二次过滤
		foreach ($match[1] as $k => $v) {
			array_push($site_arr, get_domain($v));
		}

		//数据去除重复项
		$site_arr = array_values(array_unique($site_arr));

		//拼接返回数据
		$data = array('code' => 200, 'data' => $site_arr);

	}

	//无结果时返回当前域名
	else {
		$data = array('code' => 200, 'data' => array($d));
	}

	return json_encode($data);

}

?>