<?php

	$title = '网站 ' . $d.' 的搜索引擎收录、反向链接查询';
	$keywords = '搜索引擎收录查询,搜索引擎收录入口,怎么让搜索引擎收录,禁止搜索引擎收录,搜索引擎收录口,搜索引擎收录情况,搜索引擎收录提交,搜索引擎收录大全,谷歌搜索引擎收录,雅虎搜索引擎收录';
	$description = '提供网站收录查询、搜索引擎收录查询，可以查询网站在百度、google、yahoo、sogou、soso、yodao、msn等搜索引擎的收录数量、反向链接';

	require_once('header.php');

?>
	<div class="toolbox">
		<div class="hd"><h2>网站&nbsp;&nbsp;<b class="red"><?php echo $d; ?></b>&nbsp;&nbsp;的搜索引擎收录情况</h2></div>
		<table class="bd" id="indexed-index">
			<tr>
				<td class="overview" colspan="8">
					<ul class="clearfix">
						<li>Alexa 综合排名&nbsp;&nbsp;<big><a title="点击查看 <?php echo $d; ?> Alexa 全球排名" id="indexed-alexa-rank" class="red" href="http://alexa.adminunion.com/<?php echo $d; ?>"><span class="loading"></span></a></big></li>
						<li>PageRank 值&nbsp;&nbsp;<big><a title="点击查看 <?php echo $d; ?> PageRank 值" id="indexed-pagerank" class="red" href="http://pagerank.adminunion.com/<?php echo $d; ?>"><span class="loading"></span></a></big></li>
						<li>百度快照&nbsp;&nbsp;<big><a title="点击查看 <?php echo $d; ?> 百度快照" id="indexed-baidu-snapshot" class="red" href="http://www.baidu.com/s?wd=<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></big></li>
						<li>搜狗评级&nbsp;&nbsp;<big><a title="点击查看 <?php echo $d; ?> 搜狗评级" id="indexed-sogou-rank" class="" href="http://www.sogou.com/web?query=<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></big></li>
						<li>中国网站排行&nbsp;&nbsp;<big><a title="点击查看 <?php echo $d; ?> 中国网站排行" id="indexed-china-rank" class="" href="http://www.chinarank.org.cn/overview/Info.do?url=<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></big></li>
					</ul>
				</td>
			</tr>
			<tr>
				<th width="12.5%">搜索引擎</th>
				<th width="12.5%"><img align="absmiddle" width="16" height="16" src="http://img02.taobaocdn.com/tps/i2/T1k.1TXklgXXXXXXXX-16-16.png" title="百度" alt="百度"></th>
				<th width="12.5%"><img align="absmiddle" width="16" height="16" src="http://img03.taobaocdn.com/tps/i3/T15FyUXmdgXXXXXXXX-16-16.png" title="谷歌" alt="谷歌"></th>
				<th width="12.5%"><img align="absmiddle" width="16" height="16" src="http://img03.taobaocdn.com/tps/i3/T1VVCUXkdgXXXXXXXX-16-16.png" title="雅虎" alt="雅虎"></th>
				<th width="12.5%"><img align="absmiddle" width="16" height="16" src="http://img01.taobaocdn.com/tps/i1/T1tFyUXm4gXXXXXXXX-16-16.png" title="搜狗" alt="搜狗"></th>
				<th width="12.5%"><img align="absmiddle" width="16" height="16" src="http://img01.taobaocdn.com/tps/i1/T1ypyUXm4gXXXXXXXX-16-16.png" title="搜搜" alt="搜搜"></th>
				<th width="12.5%"><img align="absmiddle" width="16" height="16" src="http://img04.taobaocdn.com/tps/i4/T1xVyUXm4gXXXXXXXX-16-16.png" title="有道" alt="有道"></th>
				<th width="12.5%"><img align="absmiddle" width="16" height="16" src="http://img02.taobaocdn.com/tps/i2/T16FyUXmXgXXXXXXXX-16-16.png" title="必应" alt="必应"></th>
			</tr>
			<tr class="indexed-index">
				<th>收录情况</th>
				<td data-m="baidu" data-q="site"><b><a title="点击查看 <?php echo $d; ?> 百度收录情况" href="http://www.baidu.com/s?wd=site%3A<?php echo $d; ?>" class="red" target="_blank"><span class="loading"></span></a></b></td>
				<td data-m="google" data-q="site"><b><a title="点击查看 <?php echo $d; ?> 谷歌收录情况" href="http://www.google.com.hk/search?hl=zh-CN&q=site%3A<?php echo $d; ?>" class="red" target="_blank"><span class="loading"></span></a></b></td>
				<td data-m="yahoo" data-q="site"><a title="点击查看 <?php echo $d; ?> 雅虎收录情况" href="http://www.yahoo.cn/s?q=site%3A<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></td>
				<td data-m="sogou" data-q="site"><a title="点击查看 <?php echo $d; ?> 搜狗收录情况" href="http://www.sogou.com/web?query=site%3A<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></td>
				<td data-m="soso" data-q="site"><a title="点击查看 <?php echo $d; ?> 搜搜收录情况" href="http://www.soso.com/q?w=site%3A<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></td>
				<td data-m="youdao" data-q="site"><a title="点击查看 <?php echo $d; ?> 有道收录情况" href="http://www.youdao.com/search?q=site%3A<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></td>
				<td data-m="bing" data-q="site"><a title="点击查看 <?php echo $d; ?> 必应收录情况" href="http://cn.bing.com/search?q=site%3A<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></td>
			</tr>
			<tr class="indexed-index">
				<th>反向链接</th>
				<td data-m="baidu" data-q="link"><b><a title="点击查看 <?php echo $d; ?> 百度反向链接" href="http://www.baidu.com/s?wd=domain%3A<?php echo $d; ?>" class="red" target="_blank"><span class="loading"></span></a></b></td>
				<td data-m="google" data-q="link"><b><a title="点击查看 <?php echo $d; ?> 谷歌反向链接" href="http://www.google.com.hk/search?hl=zh-CN&q=link%3A<?php echo $d; ?>" class="red" target="_blank"><span class="loading"></span></a></b></td>
				<td data-m="yahoo" data-q="link"><a title="点击查看 <?php echo $d; ?> 雅虎反向链接" href="http://www.yahoo.cn/s?q=link%3A<?php echo $d; ?>&bwm=i" target="_blank"><span class="loading"></span></a></td>
				<td data-m="sogou" data-q="link"><a title="点击查看 <?php echo $d; ?> 搜狗反向链接" href="http://www.sogou.com/web?query=link%3A<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></td>
				<td data-m="soso" data-q="link"><a title="点击查看 <?php echo $d; ?> 搜搜反向链接" href="http://www.soso.com/q?w=link%3A<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></td>
				<td data-m="youdao" data-q="link"><a title="点击查看 <?php echo $d; ?> 有道反向链接" href="http://www.youdao.com/search?q=link%3A<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></td>
				<td data-m="bing" data-q="link"><a title="点击查看 <?php echo $d; ?> 必应反向链接" href="http://cn.bing.com/search?q=link%3A<?php echo $d; ?>" target="_blank"><span class="loading"></span></a></td>
			</tr>
		</table>
	</div>
	<div class="toolbox">
		<div class="hd"><h2>该网站的百度、谷歌近日收录情况</h2></div>
		<table class="bd" id="indexed-recent">
			<tr>
				<td class="overview" colspan="4">百度近日收录情况</td>
				<td class="overview" colspan="4">谷歌近日收录情况</td>
			</tr>
			<tr>
				<th width="12.5%">最近一天</th>
				<th width="12.5%">最近一周</th>
				<th width="12.5%">最近一月</th>
				<th width="12.5%">最近一年</th>
				<th width="12.5%">最近一天</th>
				<th width="12.5%">最近一周</th>
				<th width="12.5%">最近一月</th>
				<th width="12.5%">最近一年</th>
			</tr>
			<tr class="indexed-recent">
				<td data-m="baidu" data-t="1"><a title="点击查看 <?php echo $d; ?> 百度最近一天收录情况" href="http://www.baidu.com/s?wd=site%3A<?php echo $d; ?>&lm=1" target="_blank"><span class="loading"></span></a></td>
				<td data-m="baidu" data-t="7"><b><a title="点击查看 <?php echo $d; ?> 百度最近一周收录情况" href="http://www.baidu.com/s?wd=site%3A<?php echo $d; ?>&lm=7" class="red" target="_blank"><span class="loading"></span></a></b></td>
				<td data-m="baidu" data-t="30"><b><a title="点击查看 <?php echo $d; ?> 百度最近一月收录情况" href="http://www.baidu.com/s?wd=site%3A<?php echo $d; ?>&lm=30" class="red" target="_blank"><span class="loading"></span></a></b></td>
				<td data-m="baidu" data-t="365"><a title="点击查看 <?php echo $d; ?> 百度最近一年收录情况" href="http://www.baidu.com/s?wd=site%3A<?php echo $d; ?>&lm=365"><span class="loading"></span></a></td>
				<td data-m="google" data-t="d"><a title="点击查看 <?php echo $d; ?> 谷歌最近一天收录情况" href="http://www.google.com.hk/search?hl=zh-CN&q=site%3A<?php echo $d; ?>&as_qdr=d" target="_blank"><span class="loading"></span></a></td>
				<td data-m="google" data-t="w"><b><a title="点击查看 <?php echo $d; ?> 谷歌最近一周收录情况" href="http://www.google.com.hk/search?hl=zh-CN&q=site%3A<?php echo $d; ?>&as_qdr=w" class="red" target="_blank"><span class="loading"></span></a></b></td>
				<td data-m="google" data-t="m"><b><a title="点击查看 <?php echo $d; ?> 谷歌最近一月收录情况" href="http://www.google.com.hk/search?hl=zh-CN&q=site%3A<?php echo $d; ?>&as_qdr=m" class="red" target="_blank"><span class="loading"></span></a></b></td>
				<td data-m="google" data-t="y"><a title="点击查看 <?php echo $d; ?> 谷歌最近一年收录情况" href="http://www.google.com.hk/search?hl=zh-CN&q=site%3A<?php echo $d; ?>&as_qdr=y" target="_blank"><span class="loading"></span></a></td>
			</tr>
		</table>
	</div>
<?php

	$js = <<<EOT
<script>
/**
 * 查询 Alexa 全球排名
 */
$.ajax({
	type: 'get',
	url: '/api.php',
	data: 'c=indexed&m=alexa_rank&d={$d}',
	success: function (d) {
		$('#indexed-alexa-rank').html(d);
	}
});

/**
 * 查询 PageRank 值
 */
$.ajax({
	type: 'get',
	url: '/api.php',
	data: 'c=pagerank&m=pagerank&d={$d}',
	success: function (d) {
		$('#indexed-pagerank').html(d);
	}
});

/**
 * 查询百度快照
 */
$.ajax({
	type: 'get',
	url: '/api.php',
	data: 'c=indexed&m=baidu_snapshot&d={$d}',
	success: function (d) {
		$('#indexed-baidu-snapshot').html(d);
	}
});

/**
 * 查询搜狗评级
 */
$.ajax({
	type: 'get',
	url: '/api.php',
	data: 'c=indexed&m=sogou_rank&d={$d}',
	success: function (d) {
		$('#indexed-sogou-rank').html(d);
	}
});

/**
 * 查询中国网站排行
 */
$.ajax({
	type: 'get',
	url: '/api.php',
	data: 'c=indexed&m=china_rank&d={$d}',
	success: function (d) {
		$('#indexed-china-rank').html(d);
	}
});

/**
 * 查询搜索引擎收录
 */
$('#indexed-index tr.indexed-index td').each(function () {
	var self = $(this),
		m = self.attr('data-m'),
		q = self.attr('data-q');
	$.ajax({
		type: 'get',
		url: '/api.php',
		data: 'c=indexed&m=' + m + '_index&d={$d}&q=' + q,
		success: function (d) {
			self.find('a').html(d);
		}
	});
});

/**
 * 查询近日收录
 */
$('#indexed-recent tr.indexed-recent td').each(function () {
	var self = $(this),
		m = self.attr('data-m'),
		t = self.attr('data-t');
	$.ajax({
		type: 'get',
		url: '/api.php',
		data: 'c=indexed&m=' + m + '_recent&d={$d}&t=' + t,
		success: function (d) {
			self.find('a').html(d);
		}
	});
});
</script>
EOT;

	require_once('footer.php');

?>