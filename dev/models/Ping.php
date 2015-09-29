<?php

// todo
class Ping extends CModel {

	function attributeNames () {
	}

	/**
	 * 重新获取域名 IP 地址
	 * @param string $host
	 * @param number $retry
	 * @return string
	 */
	function _gethostretry ($host, $retry) {

		$ip = gethostbyname($host);
		if (preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $ip)) {
			return $ip;
		} elseif (0 !== $retry--) {
			return $this->_gethostretry($ip, $retry);
		} else {
			return '';
		}

	}

	/**
	 * 对域名进行 Ping 检测
	 * @param string $q
	 * @param string $server
	 * @return array
	 */
	function lookup ($q, $server) {

		//从监测节点 Ping 检测
		if ($server) {
			$url = 'http://' . $server . '.cc.la/ajax/ping/?q=' . $q;
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

		//通过本地 Ping 检测
		$host = explode('/', str_replace('http://', '', $q));
		$host = $host[0];
		if (!$ip = $this->_gethostretry($host, 3)) {
			return array(
				'status' => 'error',
				'errorMsg' => "Ping request could not find host $host. Please check the name and try again."
			);
		}
		$rawdata = "Ping $host ($ip) with 32 bytes of data:\r\n";
		for ($i = 0; $i < 4; $i++) {
			$start = microtime(true);
			$fp = @stream_socket_client($host . ':80', $errno, $errstr, 3);
			$end = microtime(true);
			if (!$fp) {
				$rawdata .= "Request Timed Out\r\n";
				continue;
			}
			$out = "GET / HTTP/1.1\r\nHost:" . $host . "\r\nConnect:Close\r\n\r\n";
			fputs($fp, $out);
			fread($fp, 32);
			fclose($fp);
			$diff = ceil(($end - $start) * 1000);
			$time[] = $diff;
			$rawdata .= "Reply from $ip: bytes=32 time=${$diff}ms TTL=64\r\n";
		}
		$received = sizeof($time);
		$lost = 4 - $received;
		$percent = ($lost / 4) * 100;
		$min = min($time);
		$min = $min ? $min : 0;
		$max = max($time);
		$max = $max ? $max : 0;
		$avg = array_sum($time) / $received;
		$avg = ceil($avg);
		$rawdata .= <<<EOT

Ping statistics for $ip:
Packets: Sent = 4, Received = $received, Lost = $lost ($percent% loss),
Approximate round trip times in milli-seconds:
Minimum = ${min}ms, Maximum = ${max}ms, Average = ${avg}ms
EOT;
		return array(
			'ip' => $ip,
			'sent' => 4,
			'received' => $received,
			'percent' => $percent,
			'min' => $min,
			'max' => $max,
			'avg' => $avg,
			'rawdata' => $rawdata
		);

	}

}