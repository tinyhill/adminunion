<?php

/*
 * @author Findname.cn
 * created: 2010/10/19
 * updated: 2010/10/23 add .tw, .hk supported
 */
define('TLD_CN', 1);
define('TLD_COMNET', 2);
define('TLD_CC', 3);
define('TLD_ORG', 4);
define('TLD_LA', 5);
define('TLD_MOBILE', 6);
define('TLD_IN', 7);
define('TLD_INFO', 8);
define('TLD_BIZ', 9);
define('TLD_TV', 10);
define('TLD_CO', 11);
define('TLD_TW', 12);
define('TLD_HK', 13);

/**
 * 获取域名后缀
 */
function get_domain_tld($name) {

	$TLD = 0;
	$Postfix = strrchr($name, ".");

	if ($Postfix == ".cn") {
		$TLD = TLD_CN;
	} else if ($Postfix == ".com" || $Postfix == ".net") {
		$TLD = TLD_COMNET;
	} else if ($Postfix == ".cc") {
		$TLD = TLD_CC;
	} else if ($Postfix == ".org") {
		$TLD = TLD_ORG;
	} else if ($Postfix == ".la") {
		$TLD = TLD_LA;
	} else if ($Postfix == ".mobi") {
		$TLD = TLD_MOBILE;
	} else if ($Postfix == ".in") {
		$TLD = TLD_IN;
	} else if ($Postfix == ".info") {
		$TLD = TLD_INFO;
	} else if ($Postfix == ".biz") {
		$TLD = TLD_BIZ;
	} else if ($Postfix == ".tv") {
		$TLD = TLD_TV;
	} else if ($Postfix == ".co") {
		$TLD = TLD_CO;
	} else if ($Postfix == ".tw") {
		$TLD = TLD_TW;
	} else if ($Postfix == ".hk") {
		$TLD = TLD_HK;
	} else {
		return -1;
	}
	return $TLD;

}

/**
 * 获取域名前缀
 */
function get_domain_prefix($name) {

	if (strpos($name, '.') === false)
		$Prefix = $name;
	else
		$Prefix = substr($name, 0, strpos($name, '.'));
	return $Prefix;

}

/**
 * 查询 Whois 信息
 */
function whois_online ($whois_server, $domain) {

	$result = '';
	$fp = fsockopen($whois_server, 43, $errno, $errstr, 30);

	if (!$fp) {
		$result = "$errstr ($errno)<br />\n";
	} else {
		$command = $domain . "\r\n";
		fwrite($fp, $command);
		while (!feof($fp)) {
			$result .= fgets($fp, 128);
		}
		fclose($fp);
	}
	return $result;

}

/**
 * 查询 Whois 服务器
 */
function get_whois_server($whois) {

	$info = array();
	if (preg_match_all("/Whois Server: ([^\r\n]*)/i", $whois, $info)) {
		return $info[1][0];
	} else
		return '';

}

//输出 Whois 信息
if (strlen($domain) > 0) {

	$support = 1;
	$tld = get_domain_tld($domain);
	if ($tld == 1) {
		$whois_server = "whois.cnnic.net.cn";
		if (preg_match('/^[\x{4E00}-\x{9FA5}]+/u', $domain)) {
			$domain = mb_convert_encoding($domain, 'gbk', 'utf-8');
			$whois_server = "cwhois.cnnic.net.cn";
		}
	} else if ($tld == 2)
		$whois_server = "whois.internic.net";
	else if ($tld == 3)
		$whois_server = "whois.nic.cc";
	else if ($tld == 4)
		$whois_server = "whois.publicinterestregistry.net";
	else if ($tld == 5)
		$whois_server = "whois.nic.la";
	else if ($tld == 6)
		$whois_server = "whois.dotmobiregistry.net";
	else if ($tld == 7)
		$whois_server = "whois.registry.in";
	else if ($tld == 8)
		$whois_server = "whois.afilias.net";
	else if ($tld == 9)
		$whois_server = "whois.neulevel.biz";
	else if ($tld == 10)
		$whois_server = "whois.nic.tv";
	else if ($tld == 11)
		$whois_server = "whois.nic.co";
	else if ($tld == 12)
		$whois_server = "whois.twnic.net.tw";
	else if ($tld == 13)
		$whois_server = "whois.hkdnr.net.hk";
	else {
		echo "($domain) TLD:$tld no supported now.";
		$support = 0;
	}

	if ($support) {
		$whois = whois_online($whois_server, $domain);

		if ($tld == 2 || $tld == 3 || $tld == 10) {
			$whois_server = get_whois_server($whois);
			if (strlen($whois_server) > 0)
				$whois .= "\n" . whois_online($whois_server, $domain);
		}
		echo nl2br($whois);
	}

}

?>