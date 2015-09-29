<?php

/**
 * 禁止输出错误报告
 */
error_reporting(E_ALL);

/**
 * 定义应用绝对路径
 */
define('APPPATH', dirname(__FILE__) . '/application/');

/**
 * 载入通用函数库
 */
require_once (APPPATH . 'core/common.php');

/**
 * 获取待查询域名
 */
$d = get_domain($_GET['domain']);
$d = $d ? $d : 'adminunion.com';

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>站长军团 Chrome 插件</title>
<link rel="stylesheet" href="http://assets.adminunion.com/min/b=css&f=reset.css,global.css">
<style>
.chrome-more {
	padding: 5px 0 10px;
}
.chrome-button, .chrome-button span {
	background: #39F url(http://img04.taobaocdn.com/tps/i4/T1yZaVXf8oXXXXXXXX-200-29.png) no-repeat;
}
.chrome-button {
	background-position: 100% 0;
	color: #fff;
	display: block;
	height: 29px;
	line-height: 29px;
	overflow: hidden;
	font-weight: bold;
	width: 180px;
	text-align: center;
	margin: 0 auto;
	text-decoration: none;
}
.chrome-button span {
	background-position: 0 0;
	color: #FFF;
	display: block;
	margin-right: 2px;
	padding-left: 2px;
	text-shadow: 1px 1px 0 #39C;
}
.chrome-button:hover span {
	text-decoration: underline;
}
.chrome-copyright {
	color: #999;
	font-size: 10px;
	text-align: center;
}
</style>
</head>
<body>
<div class="toolbox chrome">
	<div class="hd"><h2>网站&nbsp;&nbsp;<b class="red"><?php echo $d; ?></b>&nbsp;&nbsp;信息概览</h2></div>
	<table class="bd">
		<tr>
			<td class="overview" colspan="2">
				该网站位于&nbsp;&nbsp;<b><a class="red" id="chrome-address" href="http://ip.adminunion.com/<?php echo $d; ?>" target="_blank"><?php $address = json_decode(file_get_contents('http://www.adminunion.com/api.php?c=ip&m=address&d=' . $d)); echo $address[1]; ?></a></b></li>
			</td>
		</tr>
		<tr>
			<th width="50%">Alexa 排名</th>
			<th width="50%">PageRank 值</th>
		</tr>
		<tr>
			<td><b><a title="点击查看 <?php echo $d; ?> Alexa 排名" id="chrome-alexa-rank" class="red" href="http://alexa.adminunion.com/<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></td>
			<td><b><a title="点击查看 <?php echo $d; ?> PageRank 值" id="chrome-pagerank" class="red" href="http://pagerank.adminunion.com/<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></b></td>
		</tr>
		<tr>
			<th width="50%">百度快照</th>
			<th width="50%">真实 IP 地址</th>
		</tr>
		<tr>
			<td><b><a title="点击查看 <?php echo $d; ?> 百度快照" id="chrome-baidu-snapshot" class="red" href="http://www.baidu.com/s?wd=<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></b></td>
			<td><b><a title="点击查看 <?php echo $d; ?> 真实 IP 地址" id="chrome-ip" class="red" href="http://ip.adminunion.com/<?php echo $d; ?>" target="_blank"><?php echo gethostbyname($d); ?></a></b></td>
		</tr>
		<tr>
			<th>百度收录</th>
			<th>百度反链</th>
		</tr>
		<tr>
			<td><b><a class="chrome-index red" data-m="baidu" data-q="site" title="点击查看 <?php echo $d; ?> 百度收录" id="chrome-baidu-index" href="http://www.baidu.com/s?wd=site%3A<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></b></td>
			<td><b><a class="chrome-index red" data-m="baidu" data-q="link" title="点击查看 <?php echo $d; ?> 百度反链" id="chrome-baidu-backlinks" href="http://www.baidu.com/s?wd=domain%3A<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></b></td>
		</tr>
		<tr>
			<th>谷歌收录</th>
			<th>谷歌反链</th>
		</tr>
		<tr>
			<td><b><a class="chrome-index red" data-m="google" data-q="site" title="点击查看 <?php echo $d; ?> 谷歌收录" id="chrome-google-index" href="http://www.google.com.hk/search?hl=zh-CN&q=site%3A<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></b></td>
			<td><b><a class="chrome-index red" data-m="google" data-q="link" title="点击查看 <?php echo $d; ?> 谷歌反链" id="chrome-google-backlinks" href="http://www.google.com.hk/search?hl=zh-CN&q=link%3A<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></b></td>
		</tr>
	</table>
</div>
<div class="chrome-more"><a href="http://www.adminunion.com/<?php echo $d; ?>" class="chrome-button" target="_blank"><span>点此查看详细信息&raquo;</span></a></div>
<div class="chrome-copyright">&copy; 2010-2012 AdminUnion.com</div>
<script src="http://assets.adminunion.com/min/b=js&f=jquery.js,global.js"></script>
<script>
/**
 * 获取网站的 IP 地址
 */
$.ajax({
	dataType: 'json',
	type: 'get',
	url: '/api.php',
	data: 'c=ip&m=address&d=<?php echo $d; ?>',
	success: function (d) {
		$('#chrome-ip').html(d[0]);
		$('#chrome-address').html(d[1]);
	}
});

/**
 * 获取网站的 Alexa 排名
 */
$.ajax({
	dataType: 'json',
	type: 'get',
	url: '/api.php',
	data: 'c=indexed&m=alexa_rank&d=<?php echo $d; ?>',
	success: function (d) {
		$('#chrome-alexa-rank').html(d);
	}
});

/**
 * 获取网站的 PageRank 值
 */
$.ajax({
	dataType: 'json',
	type: 'get',
	url: '/api.php',
	data: 'c=pagerank&m=pagerank&d=<?php echo $d; ?>',
	success: function (d) {
		$('#chrome-pagerank').html(d);
	}
});

/**
 * 获取网站的百度快照
 */
$.ajax({
	type: 'get',
	url: '/api.php',
	data: 'c=indexed&m=baidu_snapshot&d=<?php echo $d; ?>',
	success: function (d) {
		$('#chrome-baidu-snapshot').html(d);
	}
});

/**
 * 获取网站收录数据
 */
$('.chrome-index').each(function () {
	var self = $(this),
		m = self.attr('data-m'),
		q = self.attr('data-q');
	$.ajax({
		type: 'get',
		url: '/api.php',
		data: 'c=indexed&m=' + m + '_index&d=<?php echo $d; ?>&q=' + q,
		success: function (d) {
			self.html(d);
		}
	});
});
</script>
<div style="display:none;"><script src="http://s19.cnzz.com/stat.php?id=2893872&web_id=2893872"></script>
</body>
</html>