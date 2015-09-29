<?php

/**
 * 使用 curl 提交 XML 数据
 */
function _post_xml ($url, $params) {
    $ch = curl_init();
	$headers = array(
            'POST ' . $url . ' HTTP/1.0',
            'Content-type: text/xml;charset="utf-8"',
            'Accept: text/xml',
            'Content-length: ' . strlen($params)
        );
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

/**
 * Ping Baidu
 */
function ping_baidu ($d) {

	$baidu_xml = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
	<methodCall>
		<methodName>weblogUpdates.extendedPing</methodName>
		<params>
			<param><value><string>http://{$d}</string></value></param>
			<param><value><string>http://{$d}</string></value></param>
			<param><value><string>http://{$d}</string></value></param>
			<param><value><string>http://{$d}</string></value></param>
		</params>
	</methodCall>
EOT;
	$res = _post_xml('http://ping.baidu.com/ping/RPC2', $baidu_xml);
	if (strpos($res, '<int>0</int>'))
		return '{"code":"200"}';
    else
 		return '{"code":"400"}';
}

/**
 * Ping Google
 */
function ping_google ($d) {

	$google_xml = <<<EOT
<?xml version="1.0"?>
<methodCall>
	<methodName>weblogUpdates.extendedPing</methodName>
	<params>
		<param><value>http://{$d}</value></param>
		<param><value>http://{$d}</value></param>
		<param><value>http://{$d}</value></param>
		<param><value>http://{$d}</value></param>
	</params>
</methodCall>
EOT;
	$res = _post_xml('http://blogsearch.google.com/ping/RPC2', $google_xml);
	if (strpos($res, '<boolean>0</boolean>'))
		return '{"code":"200"}';
    else
 		return '{"code":"400"}';
}

/**
 * Ping Yahoo
 */
function ping_yahoo ($d) {

	$yahoo_xml = <<<EOT
<?xml version="1.0"?>
<methodCall>
	<methodName>weblogUpdates.extendedPing</methodName>
	<params>
		<param><value>http://{$d}</value></param>
		<param><value>http://{$d}</value></param>
		<param><value>http://{$d}</value></param>
		<param><value>http://{$d}</value></param>
	</params>
</methodCall>
EOT;
	$res = _post_xml('http://api.my.yahoo.com/RPC2', $yahoo_xml);
	if (strpos($res, '<boolean>0</boolean>'))
		return '{"code":"200"}';
    else
 		return '{"code":"400"}';
}

/**
 * Ping Youdao
 */
function ping_youdao ($d) {

	$youdao_xml = <<<EOT
<?xml version="1.0"?>
<methodCall>
	<methodName>weblogUpdates.extendedPing</methodName>
	<params>
		<param><value>http://{$d}</value></param>
		<param><value>http://{$d}</value></param>
		<param><value>http://{$d}</value></param>
		<param><value>http://{$d}</value></param>
	</params>
</methodCall>
EOT;
	$res = _post_xml('http://blog.youdao.com/ping/RPC2', $youdao_xml);
	if (strpos($res, '<boolean>0</boolean>'))
		return '{"code":"200"}';
    else
 		return '{"code":"400"}';
}

?> 