<?php

class Index extends CModel {

	function attributeNames () {
	}

	// Use curl the get the file contents
	function _curl ($url) {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_COOKIE, 'PREF=ID=7dcfd7cc17b3a5ee:FF=0:NW=1:TM=1368981580:LM=1368981580:S=V089pDt-LEdqQfeP');
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;

	}

	// 百度索引
	function baidu ($q, $lm = '', $deep = false) {

		Yii::import('ext.simple_html_dom', true);
		$q = $lm ? $q . '&lm=' . $lm : $q;
		$ret = $this->_curl('http://www.baidu.com/s?wd=' . $q);
		$ret = str_get_html($ret);
		if ($ret && $ret = $ret->find('span.nums', 0)) {
			$search = array('百度为您找到相关结果', '约', '个');
			$result = str_replace($search, '', trim($ret->plaintext));
		} else {
			$result = '0';
		}
		// 首页位置、百度快照
		if ($deep) {
			$key1 = '不在首页';
			$key2 = '暂无快照';
			if ($ret && $ret = $ret->find('span.g')) {
				foreach ($ret as $k => $v) {
					if ($r1 = $v->find('b', 0)) {
						if ($r1->plaintext === $q) {
							$key1 = $k + 1;
							$r2 = explode('/', $ret[0]->plaintext);
							$r2 = array_pop($r2);
							$regex = '/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/';
							if (preg_match($regex, $r2, $match)) {
								$key2 = $match[0];
							}
							break;
						}
					}
				}
			}
			return array(trim($result), "$key1", $key2);
		} else {
			return trim($result);
		}

	}

	// 谷歌索引
	function google ($q, $as_qdr = '') {

		Yii::import('ext.simple_html_dom', true);
		$q = $as_qdr ? $q . '&as_qdr=' . $as_qdr : $q;
		$ret = $this->_curl('http://www.google.com.hk/search?hl=zh-CN&q=' . $q);
		$ret = str_get_html($ret);
		if ($ret && $ret = $ret->find('div#resultStats', 0)) {
			$result = explode(' ', $ret->plaintext);
			$result = $result[1];
		} else {
			$result = '0';
		}
		return trim($result);

	}

	// 搜狗索引
	function sogou ($q) {

		Yii::import('ext.simple_html_dom', true);
		$ret = $this->_curl('http://www.sogou.com/web?query=' . $q);
		$ret = str_get_html($ret);
		if ($ret && $ret = $ret->find('span#scd_num', 0)) {
			$result = $ret->plaintext;
		} else {
			$result = '0';
		}
		return trim($result);

	}

	// 搜搜索引
	function soso ($q) {

		Yii::import('ext.simple_html_dom', true);
		$ret = $this->_curl('http://www.soso.com/q?w=' . $q);
		$ret = str_get_html($ret);
		if ($ret && $ret = $ret->find('div#sInfo', 0)) {
			$search = array('搜搜为您找到', '约', '条相关结果');
			$result = str_replace($search, '', $ret->plaintext);
		} else {
			$result = '0';
		}
		return trim($result);

	}

	// 有道索引
	function youdao ($q) {

		Yii::import('ext.simple_html_dom', true);
		$ret = $this->_curl('http://www.youdao.com/search?q=' . $q);
		$ret = str_get_html($ret);
		if ($ret && $ret = $ret->find('span.srd', 0)) {
			$search = array('共', '约', '条结果');
			$result = str_replace($search, '', $ret->plaintext);
		} else {
			$result = '0';
		}
		return trim($result);

	}

	// 必应索引
	function bing ($q) {

		Yii::import('ext.simple_html_dom', true);
		$ret = $this->_curl('http://cn.bing.com/search?q=' . $q);
		$ret = str_get_html($ret);
		if ($ret && $ret = $ret->find('div.sb_rc_btm', 0)) {
			$result = str_replace('条结果', '', $ret->plaintext);
		} else {
			$result = '0';
		}
		return trim($result);

	}

	// 雅虎索引
	function yahoo ($q) {

		Yii::import('ext.simple_html_dom', true);
		$ret = $this->_curl('http://www.yahoo.cn/s?q=' . $q);
		$ret = str_get_html($ret);
		if ($ret && $ret = $ret->find('div.s_info', 0)) {
			$search = array('找到相关网页', '约', '条');
			$result = str_replace($search, '', $ret->plaintext);
		} else {
			$result = '0';
		}
		return trim($result);

	}

	// 360 索引
	function so ($q) {

		Yii::import('ext.simple_html_dom', true);
		$ret = $this->_curl('http://www.so.com/s?ie=utf-8&q=' . $q);
		$ret = str_get_html($ret);
		if ($ret && $ret = $ret->find('span.nums', 0)) {
			$search = array('找到相关结果', '约', '个');
			$result = str_replace($search, '', $ret->plaintext);
		} else {
			$result = '0';
		}
		return trim($result);

	}

}