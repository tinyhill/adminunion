<?php

class Ip extends CModel {

	function attributeNames () {
	}

	// 查询域名对应 IP 地址
	function query ($q) {

		Yii::import('application.components.ip.QQWry');
		$ip = gethostbyname($q);
		$QQWry = new QQWry($ip);
		return array(
			'ip' => $ip,
			'country' => iconv('GBK', 'UTF-8//IGNORE', $QQWry->Country),
			'local' => iconv('GBK', 'UTF-8//IGNORE', $QQWry->Local)
		);

	}

	// 查询访客真实 IP 地址
	function client () {

		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;

	}

	// 查询域名或 IP 地址经纬度
	function locate ($q) {

		Yii::import('application.components.ip.*');
		@require_once('geoipcity.inc');
		@require_once('geoipregionvars.php');
		$path = Yii::getPathOfAlias('application.components.ip');
		$q = gethostbyname($q);
		$gi = geoip_open($path . '/GeoLiteCity.dat', GEOIP_STANDARD);
		$r = GeoIP_record_by_addr($gi, $q);
		geoip_close($gi);
		return array(
			'lat' => $r->latitude,
			'lng' => $r->longitude
		);

	}

}