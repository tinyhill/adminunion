	<div class="toolbox">
		<div class="hd"><h2>最近查询过的网站</h2></div>
		<div class="bd log">
<?php

	//读取最近查询记录
	$logs = get_log(date('Y/m/dH'), true);

	if ($logs) {
		echo '			<ul class="clearfix">' . "\n";
		foreach ($logs as $log) {
			echo '				<li><a href="/' . $log . '" title="' . $log . '">' . $log . '</a></li>' . "\n";
		}
		echo '			</ul>' . "\n";
	} else {
		echo '			<p class="no-result">最近一小时还没有查询记录哦</p>' . "\n";
	}

?>
		</div>
	</div>
	<div class="toolbox">
		<div class="hd"><h2>站内工具导航地图</h2></div>
		<table class="bd sitemap">
			<tr>
				<td width="98">网站信息查询</td>
				<td class="col-main"><a href="http://indexed.adminunion.com/" class="green">搜索引擎收录</a>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="http://keyword.adminunion.com/">关键词排名</a>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="http://pagerank.adminunion.com/" class="red">PageRank 值</a>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="http://alexa.adminunion.com/">Alexa 排名</a>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="http://ping.adminunion.com/" class="blue">Ping 通知</a>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="http://link.adminunion.com/" class="green">友情链接检测</a>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="http://spider.adminunion.com/" class="red">蜘蛛模拟抓取</a></td>
			</tr>
			<tr>
				<td>域名IP类查询</td>
				<td class="col-main"><a href="http://ip.adminunion.com/" class="red">IP 反查域名（同 IP 站点查询）</a>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="http://whois.adminunion.com/">域名 Whois</a>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="http://icp.adminunion.com/" class="green">ICP 网站备案</a></td>
			</tr>
		</table>
	</div>
	<div class="site-link clearfix">
		<div class="col-sub">合作伙伴</div>
		<ul class="col-main clearfix">
			<li><a href="http://billing.meyu.net/aff.php?aff=010" target="_blank">梦游科技</a></li>
			<li><a href="http://www.ittang.com/" target="_blank">江西IT堂</a></li>
			<li><a href="http://www.mangguo.org" target="_blank">芒果小站</a></li>
			<li><a href="http://meishixing.com/" target="_blank">美食行</a></li>
			<li><a href="http://shareweb.me/" target="_blank">分享网络</a></li>
			<li><a href="http://www.mangguo.de/" target="_blank">芒果主机</a></li>
			<li><a href="http://sighttp.qq.com/authd?IDKEY=e59c7452cd1131d282796888fe4d4ed49e9753bbd2898ed9" title="点击这里给我发消息" target="_blank">申请友情链接（PR>5）</a></li>
		</ul>
	</div>
</div>
<div class="footer">
	<ul class="clearfix">
		<li><a href="http://www.adminunion.com/sitemap.xml" target="_blank">网站地图</a></li>
		<li><a target="_blank" href="https://chrome.google.com/webstore/detail/jiciopodjlcihnefplabkfbeppfpfeib?hl=zh-CN">使用站长军团 Chrome 插件，一秒钟查询网站信息！</a></li>
		<li><a href="http://t.adminunion.com/" target="_blank">新浪微博</a></li>
		<li><a href="http://a.adminunion.com/" target="_blank">站长学院</a></li>
		<li><a href="http://sighttp.qq.com/authd?IDKEY=e59c7452cd1131d282796888fe4d4ed49e9753bbd2898ed9" title="点击这里给我发消息" target="_blank" class="red">广告赞助请点击这里</a></li>
	</ul>
	<p class="copyright">&copy; 2010-2012 <a href="http://www.adminunion.com/">AdminUnion.com</a> v4.0&nbsp;&nbsp;-&nbsp;&nbsp;20120430 Release&nbsp;&nbsp;-&nbsp;&nbsp;<a href="http://www.miibeian.gov.cn/" target="_blank">浙ICP备08017086号</a>&nbsp;&nbsp;-</p>
</div>
<script src="http://assets.adminunion.com/min/?b=js&f=jquery.js,global.js"></script>
<?php

	if (isset($js)) {
		echo $js;
	}

?>

<script>AU.Common.init();</script>
<div style="display:none;"><script src="http://s19.cnzz.com/stat.php?id=2893872&web_id=2893872"></script></div>
</body>
</html>
<?php

	set_log($d);

?>