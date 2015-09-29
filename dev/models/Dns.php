<?php

class Dns extends CModel {

	function attributeNames () {
	}

	// 查询域名 DNS 信息
	function query ($q, $type = 'ANY') {

		Yii::import('application.components.dns.DNSQuery');

		$server = dns_get_record($q, DNS_NS);
		$server = array_shift($server);
		$server = gethostbyname($server['target']);
		$port = 53;
		$timeout = 10;
		$query = new DNSQuery($server, $port, $timeout);
		$result = array();
		if ($results = $query->Query($q, $type)) {
			foreach ($results->results as $v) {
				$result[$v->typeid][] = get_object_vars($v);
			}
		}
		if (empty($result)) {
			die('无法解析');
		} else {
			ksort($result);
			return $result;
		}

	}

}