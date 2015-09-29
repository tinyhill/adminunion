<?php

/**
 * 简易采集蜘蛛
 */
function get_content ($url) {

	require_once (APPPATH . 'lib/Snoopy.class.php');
	
	$snoopy = new Snoopy();
	$snoopy->agent = 'Googlebot/2.1 (+http://www.google.com/bot.html)';
	$snoopy->fetch($url);

	if ($snoopy->status == 200) {
		$content = $snoopy->results;
		$encode = mb_detect_encoding($content, array('ascii', 'gb2312', 'utf-8', 'gbk'));
		if ($encode == 'EUC-CN' || $encode == 'CP936') {
			$content = @mb_convert_encoding($content, 'utf-8', 'gb2312');
		}
	} else {
		$content = $snoopy->error;
	}

	return $content;

}

// ------------------------------------------------------------------------

/**
 * 获取子域名
 */
function get_host () {

	$host = explode('.', $_SERVER['HTTP_HOST']);
	return $host[0];

}

// ------------------------------------------------------------------------

/**
 * 检索并格式化域名
 */
function get_domain ($d) {

	if ($d) {
		$d = str_replace('http://', '', $d);
		$d = explode('/', $d);
		$d = trim(strtolower($d[0]));
		$d = htmlspecialchars($d, ENT_QUOTES);

		//判断是否符合域名格式
		if (preg_match('/^((?!-)[a-zA-Z0-9-]*[a-zA-Z0-9]\.)+(aero|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel|xxx|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|az|ax|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$/i', $d, $match_domain)) {
			$d = $match_domain[0];
		} else {
			$d = '';
		}
    }
	return $d ? $d : '';

}

// ------------------------------------------------------------------------

/**
 * 从 URL 中获得参数
 */
function get_parameter ($q) {

	$q = isset($_GET[$q]) ? $_GET[$q] : NULL;
	$q = trim(strtolower($q));
	$q = htmlspecialchars($q, ENT_QUOTES);
	return $q;

}

// ------------------------------------------------------------------------

/**
 * 设置缓存过期头
 */
function set_expire ($timestamp) {

	$etag = md5($timestamp);

	if ($_SERVER['HTTP_IF_NONE_MATCH'] == $etag) {
		header('Etag:' . $etag, true, 304);
		exit();
	} else {
		header('Etag:' . $etag);
	}

}

// ------------------------------------------------------------------------

/**
 * 查询本地缓存数据
 */
function get_cache ($callback, $params, $exp_time) {

	//如果非同域缓存请求，则直接查询接口
	$host = get_host();
	if ($host != get_parameter('c') && $host != 'www') {
		return call_user_func_array($callback, $params);
	}

	//定义缓存路径
	$base_dir = APPPATH . 'cache/' . get_parameter('c') . '/';
	$sub_dir = $base_dir . get_parameter('m') . '/';
	$key = md5($_SERVER['QUERY_STRING']);

	//创建缓存目录
	if (!file_exists($base_dir)) {
		@mkdir($base_dir);
	}
	if (!file_exists($sub_dir)) {
		@mkdir($sub_dir);
	}

	$cache = new Cache($exp_time, $sub_dir);
	$query = $cache->get($key);

	//无缓存数据
	if ($query == false) {
		$query = call_user_func_array($callback, $params);
		$cache->put($key, $query);
	} else {

		//使用 etag 标记控制缓存
		set_expire(date('Ymd'));

	}
	return $query;

}

// ------------------------------------------------------------------------

/**
 * 捕获蜘蛛头信息
 */
function is_spider () {

	$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

	if(preg_match('/(Bot|Crawl|Spider|slurp|sohu-search|lycos|robozilla)/i', $user_agent)) {
		return true;
	} else {
		return false;
	}

}

// ------------------------------------------------------------------------

/**
 * 写入本地查询记录
 */
function set_log ($d) {

	//来自搜索引擎的访问一律拒绝写入
	if (is_spider() || !get_domain($d)) {
		exit;
	}

	//不存在月份目录，则创建
	$path = APPPATH . 'log/' . date('Y/m/');

	if (!file_exists($path)) {
		@mkdir($path);
	}

	$filename = $path . date('dH') . '.txt';

	if (file_exists($filename)) {

		$buffer = @file_get_contents($filename);
		$buffer = explode('|', $buffer);
		
		if (!in_array($d, $buffer)) {

			$fp = fopen($filename, 'a');
			if (flock($fp, LOCK_EX)) {
				fwrite($fp, '|' . $d);
				flock($fp, LOCK_UN);
			}
			fclose($fp);

		}

	} else {
		@file_put_contents($filename, $d);
	}
}

// ------------------------------------------------------------------------

/**
 * 读取本地查询记录
 */
function get_log ($timestamp, $latest = false) {

	$filename = APPPATH . 'log/' . $timestamp . '.txt';

	if (file_exists($filename)) {

		$buffer = @file_get_contents($filename);
		$buffer = explode('|', $buffer);

		//获取最近查询网站
		if ($latest) {
			$buffer = array_reverse($buffer);
			$buffer = array_slice($buffer, 0, 20);
		}
		return $buffer;

	} else {
		return false;
	}
}

?>