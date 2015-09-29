<?php

	$keyword = get_parameter('keyword');
	$keyword = $keyword ? $keyword : '站长';
	require_once(APPPATH . 'controller/keyword/control.php');
	require_once(APPPATH . 'lib/Cache.class.php');
	$baidu_related = get_cache('get_baidu_related', array($keyword), 3600 * 24);
	$baidu_related = json_decode($baidu_related);

	$title = '网站 ' . $d.' 的关键词分析,关键词排名查询';
	$keywords = '关键词分析工具,百度关键词分析工具,百度关键词分析,谷歌关键词分析工具,怎么分析关键词,关键词关注量分析,中文关键词分析工具,网站关键词分析,谷歌关键词分析,恶猫关键词分析器';
	$description = '提供关键词排名查询，关键词分析，关键系挖掘等关键词查询工具';

	require_once('header.php');

?>
	<div class="toolbox">
		<div class="hd"><h2>网站&nbsp;&nbsp;<b class="red"><?php echo $d; ?></b>&nbsp;&nbsp;的关键词排名查询结果</h2></div>
		<div class="bd">
			<div class="overview">网站&nbsp;&nbsp;<b class="red"><?php echo $d; ?></b>&nbsp;&nbsp;对应关键词&nbsp;&nbsp;<b class="red"><?php echo $keyword; ?></b>&nbsp;&nbsp;百度排名第&nbsp;&nbsp;<big class="red" id="keyword-baidu-rank"><span class="loading"></span></big>&nbsp;&nbsp;位，谷歌排名第&nbsp;&nbsp;<big class="red" id="keyword-google-rank"><span class="loading"></span></big>&nbsp;&nbsp;位</div>
		</div>
	</div>
	<div class="toolbox">
		<div class="hd"><h2>关键词&nbsp;&nbsp;<b class="red"><?php echo $keyword; ?></b>&nbsp;&nbsp;的相关关键词挖掘</h2></div>
		<table class="bd keyword-related">
<?php

	if ($baidu_related->code == '200') {
		echo <<<EOT
			<tr>
				<th width="50%">相关关键词</th>
				<th width="50%">推广难度</th>
			</tr>
EOT;
		$words = $baidu_related->data;
		foreach ($words as $k => $v) {
			$word = $v->word;
			$bar = $v->bar;
			$k += 1;
			echo <<<EOT
			<tr>
				<td><a href="/{$d}&keyword={$word}" title="点击挖掘 {$word} 相关关键词">{$word}</a></td>
				<td>{$bar}</td>
			</tr>
EOT;
		}
	} elseif ($baidu_related->code == '300') {
		echo <<<EOT
			<tr>
				<td class="overview">暂无相关关键词哦</td>
			</tr>\n
EOT;
	} elseif ($baidu_related->code == '400') {
		echo <<<EOT
			<tr>
				<td class="overview">查询失败，请<a href="http://keyword.adminunion.com/{$d}&keyword={$keyword}">点此重新尝试</a>哦</td>
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
 * 获取百度关键词排名
 */
$.ajax({
	type: 'get',
	url: '/api.php',
	data: 'c=keyword&m=baidu_rank&d={$d}&q={$keyword}',
	success: function (d) {
		$('#keyword-baidu-rank').html(d);
	}
});

/**
 * 获取谷歌关键词排名
 */
$.ajax({
	type: 'get',
	url: '/api.php',
	data: 'c=keyword&m=google_rank&d={$d}&q={$keyword}',
	success: function (d) {
		$('#keyword-google-rank').html(d);
	}
});

/**
 * 定义百度谷歌关键词收录
 */
function get_keyword_index (target, search_engine) {
	$(target).each(function () {
		var self = $(this);
		setTimeout(function(){
		$.ajax({
			type: 'get',
			url: '/api.php',
			data: 'c=keyword&m=' + search_engine + '_index&d={$d}&q=' + self.attr('data-keyword'),
			success: function (d) {
				self.html(d);
			}
		});
		}, 1000);
	});
}

/**
 * 获取百度谷歌关键词收录
 */
get_keyword_index('.keyword-related-baidu', 'baidu');
get_keyword_index('.keyword-related-google', 'google');
</script>
EOT;

	require_once('footer.php');

?>