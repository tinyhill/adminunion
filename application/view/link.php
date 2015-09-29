<?php

	require_once(APPPATH . 'controller/link/control.php');
	$backlinks = json_decode(get_backlinks($d));

	$title = '网站 ' . $d.' 的友情链接检测查询';
	$keywords = '友情链接批量检查,友情链接检查工具,友情链接,友情链接查询,友情链接平台,友情链接检测,交换友情链接,淘宝友情链接,丹吧友情链接,自助友情链接';
	$description = '准确的查询友情链接的情况，防止在交换友情链接时被人撤掉链接，友情链接查询速度非常快';

	require_once('header.php');

?>
	<div class="toolbox">
		<div class="hd"><h2>网站&nbsp;&nbsp;<b class="red"><?php echo $d; ?></b>&nbsp;&nbsp;的友情链接检查结果</h2></div>
		<table class="bd" id="link-site">
<?php

	if ($backlinks->code == '200') {
		echo <<<EOT
			<tr>
				<td class="overview" colspan="6">在网站&nbsp;&nbsp;<b class="red">{$d}</b>&nbsp;&nbsp;首页共找到&nbsp;&nbsp;<big class="red" id="link-num"><span class="loading"></span></big>&nbsp;&nbsp;个友情链接</td>
			</tr>
			<tr>
				<th width="10%">序号</th>
				<th width="20%">检查网站</th>
				<th width="20%">检查结果</th>
				<th width="10%">PR 值</th>
				<th width="20%">百度收录</th>
				<th width="20%">百度快照</th>
			</tr>\n
EOT;
		$sites = $backlinks->data;
		foreach ($sites as $k => $v) {
			$num = $k + 1;
			$name = $v[0];
			$url = $v[1];
			echo <<<EOT
			<tr class="link-site" data-site="{$url}">
				<td>{$num}</td>
				<td><a href="http://link.adminunion.com/{$url}" title="{$name}">{$name}</a></td>
				<td class="link-backlinked"><span class="loading"></span></td>
				<td><b><a class="red link-pagerank" href="http://pagerank.adminunion.com/{$url}" title="点击查看 {$url} PageRank 值"><span class="loading"></span></a></b></td>
				<td><a class="link-indexed" href="http://indexed.adminunion.com/{$url}" title="点击查看 {$url} 百度收录情况" target="_blank"><span class="loading"></span></a></td>
				<td><a class="link-snapshot" href="http://indexed.adminunion.com/{$url}" title="点击查看 {$url} 百度快照" target="_blank"><span class="loading"></span></a></td>
			</tr>\n
EOT;
		}
	} elseif ($backlinks->code == '300') {
		echo <<<EOT
			<tr>
				<td colspan="6" class="overview">这个网站好像没有友情链接哦</td>
			</tr>\n
EOT;
	} elseif ($backlinks->code == '400') {
		echo <<<EOT
			<tr>
				<td colspan="6" class="overview">这个网站好像无法正常访问哦</td>
			</tr>\n
EOT;
	}

?>
		</table>
	</div>
<?php

	$js = <<<EOT
<script>
/**
 * 批量查询站点回链情况
 */
$('#link-site .link-site').each(function () {
	var self = $(this),
		site = self.attr('data-site');

	//获取站点友链情况
	$.ajax({
		type: 'get',
		url: '/api.php',
		data: 'c=link&m=is_backlinked&site=' + site + '&d={$d}',
		success: function (d) {
			self.find('.link-backlinked').html(d);
		}
	});

	//获取站点 Pagerank 值
	$.ajax({
		type: 'get',
		url: '/api.php',
		data: 'c=pagerank&m=pagerank&d=' + site,
		success: function (d) {
			self.find('.link-pagerank').html(d);
		}
	});

	//获取百度收录数据
	$.ajax({
		type: 'get',
		url: '/api.php',
		data: 'c=indexed&m=baidu_index&d=' + site + '&q=site',
		success: function (d) {
			self.find('.link-indexed').html(d);
		}
	});

	//获取百度快照数据
	$.ajax({
		type: 'get',
		url: '/api.php',
		data: 'c=indexed&m=baidu_snapshot&d=' + site,
		success: function (d) {
			self.find('.link-snapshot').html(d);
		}
	});
});

/**
 * 统计友情链接数目
 */
$('#link-num').html($('.link-site').size());
</script>
EOT;

	require_once('footer.php');

?>