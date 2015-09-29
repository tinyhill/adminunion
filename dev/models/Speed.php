<?php

// todo
class Speed extends CModel {

	function attributeNames () {
	}

	/**
	 * 根据域名监测访问速度
	 * @param string $q
	 * @param string $server
	 * @return array
	 */
	function lookup ($q, $server) {

		//从监测节点抓取数据
		if ($server) {
			$url = 'http://' . $server . '.cc.la/ajax/speed/?q=' . $q;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Authorization: ' . md5($q)
			));
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			$result = curl_exec($ch);
			curl_close($ch);

			if (!$result) {
				return array(
					'status' => 'error',
					'errorMsg' => '无法访问'
				);
			} else {
				return unserialize($result);
			}
		}

		//通过本地访问抓取数据
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
		curl_setopt($ch, CURLOPT_URL, $q);
		curl_setopt($ch, CURLOPT_ENCODING, 'deflate,gzip');
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$rawdata = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);

		if (!$rawdata) {
			return array(
				'status' => 'error',
				'errorMsg' => '无法访问'
			);
		}
		$rawdata = explode("\r\n\r\n", $rawdata);
		$size = $info['size_download'] + $info['header_size'];
		$result = array(
			'ip' => gethostbyname($q),
			'status' => $info['http_code'],
			'totalTime' => $info['total_time'] * 1000,
			'dnsTime' => $info['namelookup_time'] * 1000,
			'connectTime' => $info['connect_time'] * 1000,
			'serverTime' => $info['pretransfer_time'] * 1000,
			'size' => round($size / 1024, 2),
			'speed' => round($info['speed_download'] / 1024, 2),
			'rawdata' => $rawdata[0]
		);
		return array(
			'status' => 'success',
			'result' => $result
		);

	}

}