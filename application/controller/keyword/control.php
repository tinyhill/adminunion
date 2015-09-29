<?php

/**
 * 查询百度关键词排名
 */
function get_baidu_rank ($d, $q) {

	//初始预设无排名
	$baidu_rank = 0;
	$content = @file_get_contents('http://www.baidu.com/s?q1=' . $q . '&rn=100') or die('0');

	if (preg_match_all('/<table cellpadding="0" cellspacing="0" class="result" id="(.*?)" ><tr><td class=f><h3 class="t"><a onmousedown="(.*?)target="_blank">/', $content, $match)) {

		//查找站点排名值
		$d = str_replace('www.', '', $d);

		foreach ($match[2] as $k => $v) {
			if (stripos($v, $d) !== false) {
				return $k + 1;
			}
		}

	}

	return $baidu_rank;

}

/**
 * 查询谷歌关键词排名
 */
function get_google_rank ($d, $q) {

	//初始预设无排名
	$google_rank = 0;

	$ctx = stream_context_create(array('http' => array('header' => 'Cookie: PREF=ID=fef74816681e7898:U=9ea73b7f54aa9005:FF=2:LD=zh-CN:NW=1:TM=1295952619:LM=1296005167:S=Dk6Hp_5SDKZ3OhJy;', )));
	$content = @file_get_contents('http://203.208.46.85/search?hl=zh-CN&q=' . $q . '&num=100', false, $ctx) or die('0');

	if (preg_match_all('/<h3 class="r"><a href="(.*?)" target/', $content, $match)) {

		//查找站点排名值
		$d = str_replace('www.', '', $d);

		foreach ($match[1] as $k => $v) {
			if (stripos($v, $d) !== false) {
				return $k + 1;
			}
		}

	}

	return $google_rank;

}

/**
 * 获取百度相关关键词
 * code 200 请求成功
 * code 300 没有记录
 * code 400 连接失败
 */
function get_baidu_related ($q) {

    //判断请求数据编码
    if (mb_check_encoding($q, 'UTF-8'))
        $q = mb_convert_encoding($q, 'GBK', 'UTF-8');

	$content = @file_get_contents('http://index.baidu.com/main/word.php?word=' . $q) or die('{"code":"400"}');

	if (preg_match_all('/<td><a href=".\/word.php(.*?)>(.*?)<\/a><\/td>\n<td>(.*?)<span class="bar" style="width: (.*?)%;">/', $content, $match)) {

		$q_arr = array('code' => '200', 'data' => array());

		//填充关键字数组
		foreach ($match[2] as $k => $v) {

			$match_bar = $match[4][$k];

			if ($match_bar >= 80) {
				$bar = '<span style="color:red;">≈ ' . $match_bar . '</span>';
			} elseif ($match_bar <= 20) {
				$bar = '<span style="color:green;">≈ ' . $match_bar . '</span>';
			} else {
				$bar = '<span style="color:blue;">≈ ' . $match_bar . '</span>';
			}
			array_push($q_arr['data'], array('word' => urlencode($v), 'bar' => $bar));
		}

	} else {
	    $q_arr = array('code' => '300');
	}

    $q_str = urldecode(json_encode($q_arr));

    //判断响应数据编码
    if (!mb_check_encoding($q_str, 'UTF-8'))
        $q_str = mb_convert_encoding($q_str, 'UTF-8', 'GBK');

	return $q_str;

}

?>