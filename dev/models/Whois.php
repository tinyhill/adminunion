<?php

class Whois extends CModel {

	function attributeNames () {
	}

	// 查询域名 Whois 信息
	function query ($q, $deep = false) {

		Yii::import('application.components.whois.__WHOIS__');
		$whois = new __WHOIS__();
		$whois->deep_whois = $deep;
		$result = $whois->Lookup($q);
		$result = implode('<br>', $result['rawdata']);
		if ($deep === false && in_array(strrchr($q, '.'), array('.com', '.net'))) {
			$result = explode('<<<', $result);
			$result = $result[0] . '<<<<br>';
		}
		return $result;

	}

	// 查询域名注册商记录
	function reginfo ($q) {

		Yii::import('application.components.whois.__WHOIS__');
		$whois = new __WHOIS__();
		$whois->deep_whois = false;
		$result = $whois->Lookup($q);
		return array(
			'regyinfo' => $result['regyinfo'],
			'regrinfo' => $result['regrinfo']
		);

	}

}