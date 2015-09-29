<?php

/**
 * 禁止输出错误报告
 */
error_reporting(0);

/**
 * 定义应用绝对路径
 */
define('APPPATH', dirname(__FILE__) . '/application/');

/**
 * 载入通用函数库
 */
require_once (APPPATH . 'core/common.php');

header('content-type:application/xml;charset=utf-8');

/**
 * 获取时间参数
 */
$y = get_parameter('y');
$m = get_parameter('m');
$d = get_parameter('d');
$h = get_parameter('h');

/**
 * 输出子 Sitemap 文件
 * map to /sitemap_YmdH.xml
 */
if ($y && $m && $d && $h) {

	$filepath = $y . '/' . $m . '/' . $d . $h;

	if (!file_exists(APPPATH . 'log/' . $filepath . '.txt')) {
		die('Directory access is forbidden.');
	}

	$hosts = array_diff(scandir(APPPATH . 'controller/'), array('.', '..', '.htaccess'));
	$logs = get_log($filepath);
	$lastmod = $y . '-' . $m . '-' . $d . 'T' . $h . ':00:00+00:00';

	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
	foreach ($logs as $log) {
		foreach ($hosts as $host) {
			echo '	<url>' . "\n";
			echo '		<loc>http://' . $host . '.adminunion.com/' . $log . '</loc>' . "\n";
			echo '		<lastmod>' . $lastmod . '</lastmod>' . "\n";
			echo '		<changefreq>daily</changefreq>' . "\n";
			echo '		<priority>0.2</priority>' . "\n";
			echo '	</url>' . "\n";
		}
	}
	echo '</urlset>' . "\n";

}

/**
 * 输出 Sitemap 索引文件
 * map to /sitemap.xml
 */
else {

	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

	$ys = array_diff(scandir(APPPATH . 'log/'), array('.', '..', '.htaccess'));
	foreach ($ys as $y) {

		$ms = array_diff(scandir(APPPATH . 'log/' . $y . '/'), array('.', '..', '.htaccess'));
		foreach ($ms as $m) {

			$logs = array_diff(scandir(APPPATH . 'log/' . $y . '/' . $m . '/'), array('.', '..', '.htaccess'));
			foreach ($logs as $log) {

				$log = str_replace('.txt', '', $log);
				$ds = str_split($log, 2);

				echo '	<sitemap>' . "\n";
				echo '		<loc>http://www.adminunion.com/sitemap_' . $y . $m . $log . '.xml</loc>' . "\n";
				echo '		<lastmod>' . $y . '-' . $m . '-' . $ds[0] . 'T' . $ds[1] . ':00:00+00:00</lastmod>' . "\n";
				echo '	</sitemap>' . "\n";

			}
		}
	}

	echo '</sitemapindex>' . "\n";

}

?>