<?php

	$title = '站长军团，我的站长我的站！';
	$keywords = '网站收录查询,站长查询,百度排名,百度权重查询,关键词排名查询,百度收录查询';
	$description = '站长军团提供网站收录查询和站长查询以及百度权重值查询等多个站长工具，免费查询各种工具，包括有关键词排名查询，百度收录查询等。';

	require_once('header.php');

?>
	<div class="toolbox">
		<div class="hd"><h2>网站信息查询工具</h2></div>
		<table class="bd" id="ip-reverse">
			<tr>
				<td width="20%" class="overview"><b><a href="http://indexed.adminunion.com/<?php echo $d; ?>" class="green">搜索引擎收录</a></b><p class="gray">搜索引擎收录、反链查询</p></td>
				<td width="20%" class="overview"><b><a href="http://ip.adminunion.com/<?php echo $d; ?>" class="red">IP 反查域名</a></b><p class="gray">同一服务器网站数量查询</p></td>
				<td width="20%" class="overview"><b><a href="http://pagerank.adminunion.com/<?php echo $d; ?>" class="green">PageRank 值</a></b><p class="gray">谷歌网站权重值查询</p></td>
				<td width="20%" class="overview"><b><a href="http://keyword.adminunion.com/<?php echo $d; ?>">关键词分析</a></b><p class="gray">关键词排名查询、挖掘工具</p></td>
				<td width="20%" class="overview"><b><a href="http://alexa.adminunion.com/<?php echo $d; ?>">Alexa 排名</a></b><p class="gray">Alexa 全球综合排名查询</p></td>
			</tr>
			<tr>
				<td class="overview"><b><a href="http://link.adminunion.com/<?php echo $d; ?>">友情链接检查</a></b><p class="gray">网站友情链接检测查询</p></td>
				<td class="overview"><b><a href="http://spider.adminunion.com/<?php echo $d; ?>">蜘蛛模拟抓取</a></b><p class="gray">模拟蜘蛛爬行网站页面</p></td>
				<td class="overview"><b><a href="http://ping.adminunion.com/<?php echo $d; ?>">Ping 通知</a></b><p class="gray">搜索引擎内容更新通知</p></td>
				<td class="overview"><b><a href="http://whois.adminunion.com/<?php echo $d; ?>">域名 Whois</a></b><p class="gray">支持全后缀域名信息查询</p></td>
				<td class="overview"><b><a href="http://icp.adminunion.com/<?php echo $d; ?>">ICP 网站备案</a></b><p class="gray">工信部网站备案信息查询</p></td>
			</tr>
		</table>
	</div>
<?php

	require_once('footer.php');

?>