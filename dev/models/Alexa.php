<?php

class Alexa extends CModel {

	function attributeNames () {
	}

	// Use curl the get the file contents
	function _curl ($url) {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_COOKIE, 'PREF=ID=7dcfd7cc17b3a5ee:FF=0:NW=1:TM=1368981580:LM=1368981580:S=V089pDt-LEdqQfeP');
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;

	}

	// 将 SimpleXMLElement 转换为数组
	function _xmltoarr ($xml, $root = true) {

		if (!$xml->children()) {
			return (string)$xml;
		}
		$array = array();
		foreach ($xml->children() as $element => $node) {
			$totalElement = count($xml->{$element});
			if (!isset($array[$element])) {
				$array[$element] = "";
			}
			// Has attributes
			if ($attributes = $node->attributes()) {
				$data = array(
					'attributes' => array(),
					'value' => (count($node) > 0) ? $this->_xmltoarr($node, false) : (string)$node
				);
				foreach ($attributes as $attr => $value) {
					$data['attributes'][$attr] = (string)$value;
				}
				if ($totalElement > 1) {
					$array[$element][] = $data;
				} else {
					$array[$element] = $data;
				}
				// Just a value
			} else {
				if ($totalElement > 1) {
					$array[$element][] = $this->_xmltoarr($node, false);
				} else {
					$array[$element] = $this->_xmltoarr($node, false);
				}
			}
		}
		if ($root) {
			return array($xml->getName() => $array);
		} else {
			return $array;
		}

	}

	// 查询 Alexa 基础数据
	function query ($q) {

		$url = 'http://data.alexa.com/data?cli=10&dat=snba&ver=7.0&cdt=alx_vw=20&url=' . $q;
		if (!$ret = $this->_curl($url)) {
			return array(
				'status' => 'error',
				'errorMsg' => '无法访问'
			);
		}
		$ret = str_replace(array("\n", "\r", "\t"), '', $ret);
		$ret = trim(str_replace('"', "'", strtolower($ret)));
		$result = simplexml_load_string($ret);
		$result = $this->_xmltoarr($result, false);
		return array(
			'status' => 'success',
			'result' => $result
		);

	}

}