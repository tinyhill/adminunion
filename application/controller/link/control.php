<?php

/**
 * 获取友情链接数据
 */
function get_backlinks ($d) {

	$out = @file_get_contents('http://' . $d) or die('{"code":"400"}');

	$out = str_replace(array("\r\n", "\r", "\n"), '', $out);

	if (!mb_check_encoding($out, 'UTF-8'))
		$out = mb_convert_encoding($out, 'UTF-8', 'GBK');

	//挖掘页面中的链接
	if (preg_match_all('/<a(.*?)href=(.*?)>(.*?)<\/a>/i', $out, $m)) {

		$d = str_replace('www.', '', $d);
		$site_arr = array();

		//拼装站点数据
		foreach ($m[2] as $k => $v) {

			$v = explode(' ', $v);
			$v = $v[0];
			$v = str_replace(array("'", '"'), '', $v);
			$v = get_domain($v);

			$name = $m[3][$k];
			
			//图片链接
			if (preg_match('/<img(.*?)src=(.*?)>/i', $name)) {
				$name = '图片链接';
			} else {
				$name = strip_tags($name);
			}

			if (strpos($v, $d) === false && $name && $v) {
				array_push($site_arr, array(urlencode($name), $v));
			}

		}

		//$site_arr = array_values(array_unique($site_arr));
		$site = array('code' => '200', 'data' => $site_arr);

		if (count($site_arr)) {
			return urldecode(json_encode($site));}
		else {
			return '{"code":"300"}';
		}

	} else {
		return '{"code":"300"}';
	}

}

/**
 * 检查回链情况
 */
function is_backlinked ($site, $d) {

	$out = @file_get_contents('http://' . $site) or die('<span style="color:gray;">无法打开</span>');

	$out = str_replace(array("\r\n", "\r", "\n"), '', $out);

	if (!mb_check_encoding($out, 'UTF-8'))
		$out = mb_convert_encoding($out, 'UTF-8', 'GBK');

	//判断是否有反链
	if (preg_match('/<a(.*?)href=(.*?)' . $d . '(.*?)>(.*?)<\/a>/i', $out)) {
		return '<span style="color:green;">有链接</span>';
	} else {
		return '<span style="color:red;">无链接</span>';
	}

}

?>