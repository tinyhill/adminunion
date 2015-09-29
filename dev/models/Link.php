<?php

class Link extends CModel {

	private $info, $ret;

	function attributeNames () {
	}

	// 检查并转换编码
	function _encode ($str) {

		$encoding = array('ASCII', 'GB2312', 'GBK', 'UTF-8');
		return @mb_convert_encoding($str, 'UTF-8', $encoding);

	}

	// 查询主机名称
	function _host ($url) {

		if ($host = parse_url($url, PHP_URL_HOST)) {
			return $host;
		} else {
			$host = parse_url($url, PHP_URL_PATH);
			$host = strpos($host, '/') === 0 ? substr($host, 1) : $host;
			$host = explode('/', $host, 2);
			return array_shift($host);
		}

	}

	// 查询注册域名
	function _registered ($url) {

		$suffix = dirname(__FILE__) . '/../config/suffix.php';
		$suffix = @require($suffix);
		$host = $this->_host($url);
		$ret = explode('.', $host);
		$c = array_pop($ret);
		$b = array_pop($ret);
		$a = array_pop($ret);
		$registered = $b . '.' . $c;
		if (in_array($b, $suffix[$c])) {
			return $a ? $a . '.' . $registered : $registered;
		} else {
			return $registered;
		}

	}

	// 根据蜘蛛查询用户代理
	function _ua ($robot) {

		if ($robot === 'baidu') {
			$ua = 'Baiduspider+(+http://www.baidu.com/search/spider.htm)';
		} elseif ($robot === 'google') {
			$ua = 'Googlebot/2.1 (+http://www.google.com/bot.html)';
		} else {
			$ua = $_SERVER['HTTP_USER_AGENT'];
		}
		return $ua;

	}

	// Use curl the get the file contents
	function _curl ($url, $ua = '') {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->_ua($ua));
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$data = curl_exec($ch);
		$this->info = curl_getinfo($ch);
		curl_close($ch);
		return $data;

	}

	// 检测蜘蛛访问权限
	function _allow ($url, $ua = false) {

		if (!$ua) return true;
		$host = $this->_host($url);
		$url = 'http://' . $host . '/robots.txt';
		if (!$robots = @file($url, FILE_SKIP_EMPTY_LINES)) {
			return true;
		}
		$ruleApplies = false;
		foreach ($robots as $line) {
			if (preg_match('/^\s*User-agent:(.*)/i', $line, $match)) {
				$ruleApplies = preg_match("/(\*|$ua)/i", $match[1]);
			}
			if ($ruleApplies && preg_match('/^\s*Disallow:(.*)/i', $line, $regs)) {
				if (trim($regs[1]) === '/') return false;
			}
		}
		return true;

	}

	// 查询外链
	function query ($q, $ua = '', $links = '') {

		Yii::import('ext.simple_html_dom', true);

		// 手动输入链接
		if ($links) {
			$links = explode(',', $links);
			$result = array();
			foreach ($links as $v) {
				$host = $this->_host($v);
				if (!array_key_exists($host, $result)) {
					$result[$host] = trim(strtolower($v));
				}
			}
			return $result;
		}

		// 自动分析链接
		if ($ret = $this->_curl($q, $ua)) {
			$ret = $this->_encode($ret);
			$ret = str_get_html($ret);
			$ret = $ret->find('a[href]');
			$this->ret = $ret;
		} else {
			exit('无法访问');
		}

		// 过滤站外链接
		$registered = $this->_registered($q);
		$result = array();
		foreach ($ret as $v) {
			if ($href = $v->href) {
				if (strpos($href, 'http://') === 0) {
					$host = $this->_host($href);
					$str = substr($host, 0 - strlen($registered));
					if ($str !== $registered) {
						if (!array_key_exists($host, $result)) {
							$text = trim($v->plaintext);
							$result[$host]['text'] = $text ? $text : '链接名称为空';
							$result[$host]['href'] = $href;
							$result[$host]['rel'] = trim($v->rel);
							if ($v->find('img', 0)) {
								$result[$host]['text'] = '图片链接';
							}
						}
					}
				}
			}
		}
		return $result;

	}

	// 查询反链
	function backlink ($q, $url, $ua) {

		// 检测蜘蛛权限
		if ($ua && !$allow = $this->_allow($url, $ua)) {
			return '屏蔽蜘蛛访问';
		}

		// 采集站外链接
		$ret = $this->query($url, $ua);

		// 检测内容类型
		$contentType = $this->info['content_type'];
		if (strpos($contentType, 'text/html') === false) {
			return '不是网页';
		}

		// 无站外链接
		if (empty($ret)) {
			return array(
				'number' => '0',
				'message' => '首页无反链'
			);
		};

		// 检查首页反链
		$host = $this->_host($q);
		$host = preg_replace('/^www./', '', $host);
		$i = 0;
		foreach ($ret as $k => $v) {
			$i = $i + 1;
			if ($host === preg_replace('/^www./', '', $k)) {
				$result = $v;
				$result['number'] = $i . '/' . count($ret);
				$result['message'] = '首页有反链';
				break;
			}
		}

		// 检查内页反链
		if (empty($result)) {

			// 查找友情链接页
			foreach ($this->ret as $v) {
				if ($href = $v->href) {
					if (trim($v->plaintext === '友情链接')) {
						if (strpos($href, 'http://') === 0) {
							$link = $href;
						} else {
							$href = strpos($href, '/') === 0 ? $href : '/' . $href;
							$link = $this->_host($url) . $href;
						}
						break;
					}
				}
			}

			// 分析友情链接页
			if (isset($link) && $ret = $this->query($link)) {
				$j = 0;
				foreach ($ret as $k => $v) {
					$j = $j + 1;
					if ($host === preg_replace('/^www./', '', $k)) {
						$result = $v;
						$result['number'] = $j . '/' . count($ret);
						$result['message'] = '内页有反链';
						break;
					}
				}
				if (empty($result)) {
					return array(
						'number' => "$i",
						'message' => '首页内页均无反链'
					);
				}
			} else {
				return array(
					'number' => "$i",
					'message' => '首页无反链'
				);
			}
		}
		return $result;

	}

}