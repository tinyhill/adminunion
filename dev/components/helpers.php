<?php

// 将字符串编码为 utf-8 格式
if (!function_exists('encode')) {

	function encode ($str) {

		if (!@mb_check_encoding($str, 'UTF-8')) {
			$str = @mb_convert_encoding($str, 'UTF-8', @mb_detect_encoding($str));
		}
		return $str;

	}

}

/**
 * 全局函数定义
 * @ref http://www.yiiframework.com/wiki/31/
 */

/**
 * 过滤字符串中的主机名
 * @param string $url
 * @return string
 */
function getHost ($url) {

	if ($parts = parse_url($url)) {
		$host = str_replace(' ', '', $parts['host']);
	} else {
		$host = str_replace(array('http://', ' '), '', $url);
		$host = explode('/', $host);
		$host = array_shift($host);
	}
	$suffix = dirname(__FILE__) . '/config/suffix.php';
	$suffix = require_once($suffix);
	$tld = explode('.', strtolower($host));
	$tld = array_pop($tld);
	$regex = '/^(?:[a-z0-9-]+\.)+' . $tld . '$/';
	if (isset($suffix[$tld]) && preg_match($regex, $host)) {
		return $host;
	} else {
		return '';
	}

}

/**
 * 过滤字符串中的域名
 * @param string $url
 * @return string
 */
function getRegisted ($url) {

	if ($host = gethostname($url)) {
		$suffix = dirname(__FILE__) . '/config/suffix.php';
		$suffix = require_once($suffix);
		$parts = explode('.', $host);
		$tld = array_pop($parts);
		$sld = array_pop($parts);
		$pre = array_pop($parts);
		$registed = $sld . '.' . $tld;
		if (in_array($sld, $suffix[$tld])) {
			return $pre ? $pre . '.' . $registed : $registed;
		} else {
			return $registed;
		}
	} else {
		return '';
	}

}

/**
 * 过滤字符串中的 IP 地址
 * @param string $ip
 * @return string
 */
function getIp ($ip) {

	$regex = '/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/';
	$ip = str_replace(' ', '', $ip);
	if (preg_match($regex, $ip, $match)) {
		$parts = explode('.', $ip);
		foreach ($parts as $v) {
			if ($v < 0 || $v > 255) {
				return '';
			}
		}
		return $ip;
	} else {
		return '';
	}

}