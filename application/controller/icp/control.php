<?php

require_once ('validate/Validate.class.php');

/**
 * 获得图形验证码
 */
function _get_img () {

	$sid = '';
	if (is_file(dirname(__FILE__) . '/validate/JSESSIONID.dat'))
		$sid = file_get_contents(dirname(__FILE__) . '/validate/JSESSIONID.dat');

	$fp = @fsockopen('jscainfo.miitbeian.gov.cn', 80, $errno, $errstr, 5) or die('查询失败');
	stream_set_timeout($fp, 1);

	if (!$fp) {
		echo "$errstr ($errno)<br>\n"; 
	} else {
		$out = 'GET /validateCode HTTP/1.1
Host: jscainfo.miitbeian.gov.cn
Referer: http://jscainfo.miitbeian.gov.cn/icp/publish/query/icpMemoInfo_showPage.action
Accept: */*
User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) 
AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.215 Safari/534.10
Accept-Encoding: gzip,deflate,sdch
Accept-Language: zh-CN,zh;q=0.8
Accept-Charset: GBK,utf-8;q=0.7,*;q=0.3
Cookie: JSESSIONID=' . $sid. '

';
		fwrite($fp, $out);
		while ($f = fgets($fp, 1024)) {
			if ($f == "\r\n")
				break;
			if (preg_match('/Set-Cookie: JSESSIONID=(\S+); Path/', $r, $m)) {
				$sid = $m[1];
				file_put_contents(dirname(__FILE__) . '/validate/JSESSIONID.dat', $sid);
			}
		}
		fgets($fp, 1024);
		$r = '';
		$t = 0;
		while ($t < 2) {
			$r .= fread($fp, 102400);
			$t++;
		}
		fclose($fp);
		return $r;
	}

}

/**
 * 计算图形验证码
 */
function _get_code ($path) {

	file_put_contents($path, _get_img());

	//获取验证码算术结果
	$validate = new Validate();
	$validate -> setImage($path);
	$validate -> getHec();

	$code = $validate -> run();
	return $code;

}

/**
 * 发送并获得查询数据
 */
function _hack_code ($d, $code) {

	$sid = file_get_contents(dirname(__FILE__) . '/validate/JSESSIONID.dat');
	$parameters = 'siteName=&condition=1&siteDomain=' . $d . '&siteUrl=&mainLicense=&siteIp=&unitName=&mainUnitNature=-1&certType=-1&mainUnitCertNo=&verifyCode=' . $code;
	$str_len = strlen($parameters);

	$fp = fsockopen('jscainfo.miitbeian.gov.cn', 80, $errno, $errstr, 5);
	stream_set_timeout($fp, 1);

	if (!$fp) {
		echo "$errstr ($errno)<br>\n";
	} else {
		$out = 'POST /icp/publish/query/icpMemoInfo_searchExecute.action;jsessionid=' . $sid . ' HTTP/1.1
Host: jscainfo.miitbeian.gov.cn
Referer: http://jscainfo.miitbeian.gov.cn/icp/publish/query/icpMemoInfo_showPage.action
Content-Length: ' . $str_len . '
Cache-Control: max-age=0
Origin: http://jscainfo.miitbeian.gov.cn
Content-Type: application/x-www-form-urlencoded
Accept: application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5
User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.215 Safari/534.10
Accept-Encoding: gzip,deflate,sdch
Accept-Language: zh-CN,zh;q=0.8
Accept-Charset: GBK,utf-8;q=0.7,*;q=0.3
Cookie: JSESSIONID=' . $sid . '

' . $parameters;

		fwrite($fp, $out);
		while ($f = fgets($fp, 1024)) {
			if ($f == "\r\n")
				break;
			if (preg_match('/Set-Cookie: JSESSIONID=(\S+); Path/', $f, $m)) {
				$sid = $m[1];
				file_put_contents(dirname(__FILE__) . '/validate/JSESSIONID.dat', $sid);
			}
		}
		fgets($fp, 1024);
		$r = '';
		$t = 0;
		while ($t < 20) {
			$d = fread($fp, 102400);
			if (strlen($d) == 0)
				break;
			$r .= $d;
			$t++;
		}
		fclose($fp);
		return $r;
	}

}

/**
 * 挖掘页面原始数据
 */
function get_icp ($d) {

	$content = _hack_code($d, _get_code(dirname(__FILE__) . '/validate/JSESSIONCODE.dat'));

	if ($content) {

		//转码过滤页面内容
		$content = iconv('gbk', 'utf-8', $content);
		$content = str_replace('&nbsp;', '', $content);

		//非超时或错误情况
		if (preg_match_all('/<td align=\"center\" class=\"bxy\">([\w\W]*?)<\/td>/', $content, $matches)) {

			$result = $matches[1];

			$data = array(
				'unit_name' => urlencode($result[0]),
				'unit_type' => urlencode($result[1]),
				'main_license' => urlencode(trim(strip_tags($result[2]))),
				'site_name' => urlencode($result[3]),
				'site_url' => urlencode(trim(strip_tags($result[4]))),
				'verify_date' => urlencode($result[5])
			);

		}

		//验证码超时或计算错误
		elseif (preg_match('/验证码超时或计算错误/i', $content)) {

			$data = array(
				'unit_name' => urlencode('查询超时'),
				'unit_type' => urlencode('查询超时'),
				'main_license' => urlencode('查询超时'),
				'site_name' => urlencode('查询超时'),
				'site_url' => urlencode('查询超时'),
				'verify_date' => urlencode('查询超时')
			);

			die(urldecode(json_encode($data)));

		}

		//没有备案记录情况
		else {

			$data = array(
				'unit_name' => urlencode('没有记录'),
				'unit_type' => urlencode('没有记录'),
				'main_license' => urlencode('没有记录'),
				'site_name' => urlencode('没有记录'),
				'site_url' => urlencode('没有记录'),
				'verify_date' => urlencode('没有记录')
			);
			die(urldecode(json_encode($data)));

		}

	} else {

		$data = array(
			'unit_name' => urlencode('查询失败'),
			'unit_type' => urlencode('查询失败'),
			'main_license' => urlencode('查询失败'),
			'site_name' => urlencode('查询失败'),
			'site_url' => urlencode('查询失败'),
			'verify_date' => urlencode('查询失败')
		);
		die(urldecode(json_encode($data)));

	}

	return urldecode(json_encode($data));

}
?>