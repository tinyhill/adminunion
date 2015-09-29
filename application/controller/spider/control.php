<?php

/**
 * 蜘蛛模拟抓取
 */
function get_spider ($d) {

	$out = @file_get_contents('http://' . $d) or die('{"code":"400"}');
	$size = strlen($out);
	$out = str_replace(array("\r\n", "\r", "\n"), '', $out);
	$out = str_replace('&nbsp;', ' ', $out);
	$out = preg_replace('/<style(.*?)<\/style>/i', '', $out);
	$out = preg_replace('/<script(.*?)<\/script>/i', '', $out);
	$out = preg_replace('/<embed(.*?)<\/embed>/i', '', $out);
	$out = preg_replace('/<iframe(.*?)<\/iframe>/i', '', $out);
	$out = preg_replace('/<object(.*?)<\/object>/i', '', $out);
	$out = preg_replace('/\t+/i', ' ', $out);
	$out = preg_replace('/\s+/i', ' ', $out);
	if (!mb_check_encoding($out, 'UTF-8'))
		$out = mb_convert_encoding($out, 'UTF-8', 'GBK');

	//获取标题
	if (preg_match('/<title>(.*?)<\/title>/i', $out, $match)) {
		$title = trim($match[1]);
		$content = $title;
	} else {
		$title = '<span class="red">没有标题信息</span>';
	}

	//获取关键词
	if (preg_match('/<meta name="keywords" content="(.*?)"/i', $out, $match)) {
		$keywords = trim($match[1]);
	} else {
		$keywords = '<span class="red">没有关键词信息</span>';
	}

	//获取描述
	if (preg_match('/<meta name="description" content="(.*?)"/i', $out, $match)) {
		$description = trim($match[1]);
	} else {
		$description = '<span class="red">没有描述信息</span>';
	}

	//获取内容
	if (preg_match('/<body(.*?)>(.*)<\/body>/i', $out, $match)) {
		$content = $content . ' ' . trim(preg_replace('/\s+/', ' ', strip_tags($match[2])));
	}

	return json_encode(array(
		'code' => '200',
		'data' => array(
			'size' => round($size / 1024, 2) . 'KB',
			'title' => $title,
			'keywords' => $keywords,
			'description' => $description,
			'content' => $content
		)
	));

}

?>