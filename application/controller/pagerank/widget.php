<?php

define('BASEPATH', dirname(__FILE__) . '/');

/**
 * 根据参数生成挂件图片
 */
$domain = get_parameter('domain');
$size = get_parameter('size');

//预处理参数
$size = $size ? $size : 'xl';

//根据参数读取图片
$pagerank = @file_get_contents('http://www.adminunion.com/api.php?c=pagerank&m=pagerank&d=' . $domain . '&nocache=true');
$image = imagecreatefromgif(BASEPATH . 'src/' . $size . $pagerank . '.gif');

header('content-type:image/gif');
imagegif($image);

//释放内存空间
imagedestroy($image);

?>