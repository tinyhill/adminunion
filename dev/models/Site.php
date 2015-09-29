<?php

class Site extends CModel {

	private $info, $headers;

	function attributeNames () {
	}

	// 检查并转换编码
	function _encode ($str) {

		$encoding = array('ASCII', 'GB2312', 'GBK', 'UTF-8');
		return @mb_convert_encoding($str, 'UTF-8', $encoding);

	}

	// Use curl the get the file contents
	function _curl ($url) {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_ENCODING, 'deflate,gzip');
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$data = curl_exec($ch);
		$this->info = curl_getinfo($ch);
		$size = $this->info['header_size'];
		$headers = substr($data, 0, $size);
		$headers = explode("\r\n\r\n", trim($headers));
		$this->headers = array_shift($headers);
		curl_close($ch);
		return substr($data, $size);

	}

	// 查询页面信息
	function query ($q) {

		Yii::import('ext.simple_html_dom', true);

		// 抓取页面数据
		if ($ret = $this->_curl($q)) {
			$ret = $this->_encode($ret);
			$ret = str_get_html($ret);
			$result = array();
		} else {
			die('无法访问');
		}

		// 标题信息
		if ($title = $ret->find('title', 0)) {
			$title = trim($title->plaintext);
		}
		$result['title']['text'] = $title ? $title : '';
		$result['title']['length'] = mb_strlen($title, 'UTF-8');

		// 关键词信息
		if ($keywords = $ret->find('meta[name=keywords]', 0)) {
			$keywords = trim($keywords->content);
			$glossary = explode(',', $keywords);
			$text = trim($ret->plaintext);
			$text = str_replace('   ', '', $text);
			$tkn = strlen($text);
			foreach ($glossary as $k => $v) {
				$nkr = substr_count($text, $v);
				$nwp = strlen($v);
				$ratio = ($nkr * $nwp / $tkn) * 100;
				$glossary[$k] = array(
					'text' => $v,
					'number' => $nkr,
					'ratio' => round($ratio, 2)
				);
			}
		} else {
			$keywords = '';
			$glossary = array();
		}
		$result['keywords']['text'] = $keywords ? $keywords : '';
		$result['keywords']['length'] = mb_strlen($keywords, 'UTF-8');

		// 描述信息
		if ($desc = $ret->find('meta[name=description]', 0)) {
			$desc = trim($desc->content);
		}
		$result['description']['text'] = $desc ? $desc : '';
		$result['description']['length'] = mb_strlen($desc, 'UTF-8');

		// 关键词数据信息
		$result['glossary'] = $glossary;

		// 链接信息
		$a = $ret->find('a[href]');
		$links['in'] = 0;
		$links['out'] = 0;
		foreach ($a as $v) {
			if ($href = $v->href) {
				if (stripos($href, $q)) {
					$links['in']++;
				} else {
					$links['out']++;
				}
			}
		}

		// 头信息
		$result['headers'] = $this->headers;

		// 压缩信息
		$strlen = strlen($ret);
		$size = $this->info['size_download'];
		$ratio = 1 - $size / $strlen;
		if ($strlen > $size) {
			$gzip = array(
				'enabled' => true,
				'raw' => round($strlen / 1024, 2),
				'compressed' => round($size / 1024, 2),
				'ratio' => round($ratio, 4) * 100
			);
		} else {
			$gzip = array(
				'enabled' => false,
				'raw' => $strlen
			);
		}
		$result['gzip'] = $gzip;

		// 加载时间信息
		$result['time'] = $this->info['total_time'];

		// 样式、脚本信息
		$result['styles']['in'] = count($ret->find('style'));
		$result['styles']['out'] = count($ret->find('link[rel=stylesheet]'));
		$result['scripts']['in'] = count($ret->find('script[!src]'));
		$result['scripts']['out'] = count($ret->find('script[src]'));
		return $result;

	}

}