<?php

	function utf8_strlen ($string = null) {
		preg_match_all('/./us', $string, $match);
		return count($match[0]);
	}

	$title = '网站 ' . $d.' 的百度、谷歌蜘蛛模拟抓取查询';
	$keywords = '百度蜘蛛模拟,模拟蜘蛛抓取,蜘蛛模拟器,蜘蛛侠模拟器,终极蜘蛛侠模拟器,蜘蛛爬行模拟,模拟搜索引擎蜘蛛,蜘蛛侠模拟器下载,搜索蜘蛛模拟工具';
	$description = '模拟搜索引擎蜘蛛爬行网站页面的收录内容';

	require_once('header.php');

?>
	<div class="toolbox">
		<div class="hd"><h2>网站&nbsp;&nbsp;<b class="red"><?php echo $d; ?></b>&nbsp;&nbsp;的蜘蛛模拟抓取结果</h2></div>
<?php

	require_once(APPPATH . 'controller/spider/control.php');
	$spider = json_decode(get_spider($d));

	if ($spider->code == '200') {
		$data = $spider->data;

?>
		<table class="bd spider-meta">
			<tr>
				<td class="overview" colspan="4">网站&nbsp;&nbsp;<b class="red"><?php echo $d; ?></b>&nbsp;&nbsp;页面大小为&nbsp;&nbsp;<big class="red"><?php echo $data->size; ?></big>，建议控制在&nbsp;&nbsp;<b class="red">100KB</b>&nbsp;&nbsp;限值以内</td>
			</tr>
			<tr>
				<th width="15%">类型</th>
				<th width="45%">内容</th>
				<th width="20%">字符长度</th>
				<th width="20%">优化建议</th>
			</tr>
			<tr>
				<th>标&nbsp;&nbsp;&nbsp;题</th>
				<td><?php echo $data->title; ?></td>
				<td><?php if (utf8_strlen($data->title) > 80) echo '<span class="red">共 ' . utf8_strlen($data->title) . ' 字符</span>'; else echo '<span class="green">共 ' . utf8_strlen($data->title) . ' 字符</span>'; ?></td>
				<td>不超过 80 字符</td>
			</tr>
			<tr>
				<th>关键词</th>
				<td><?php echo $data->keywords; ?></td>
				<td><?php if (utf8_strlen($data->keywords) > 100) echo '<span class="red">共 ' . utf8_strlen($data->keywords) . ' 字符</span>'; else echo '<span class="green">共 ' . utf8_strlen($data->keywords) . ' 字符</span>'; ?></td>
				<td>不超过 100 字符</td>
			</tr>
			<tr>
				<th>描&nbsp;&nbsp;&nbsp;述</th>
				<td><?php echo $data->description; ?></td>
				<td><?php if (utf8_strlen($data->description) > 80) echo '<span class="red">共 ' . utf8_strlen($data->description) . ' 字符</span>'; else echo '<span class="green">共 ' . utf8_strlen($data->description) . ' 字符</span>'; ?></td>
				<td>不超过 200 字符</td>
			</tr>
		</table>
<?php

	} elseif ($spider->code == '400') {
		echo <<<EOT
		<table class="bd">
			<tr>
				<td class="overview">该网站好像无法访问哦</td>
			</tr>
		</table>
EOT;
	}

?>
	</div>
<?php

	if ($spider->code == '200') {
		$content = $data->content ? $data->content : '没有内容信息';
		echo <<<EOT
	<div class="toolbox">
		<div class="hd"><h2>蜘蛛模拟抓取内容</h2></div>
		<div class="bd spider-data">
{$content}
		</div>
	</div>
EOT;
	}

?>
<?php

	require_once('footer.php');

?>