<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title><?php echo $title; ?></title>
<meta name="keywords" content="<?php echo $keywords; ?>">
<meta name="description" content="<?php echo $description; ?>">
<link rel="stylesheet" href="http://assets.adminunion.com/min/?b=css&f=reset.css,global.css">
</head>
<body>
<div class="site-nav">
	<div class="box">
		<strong class="ip-address" id="ip-address" data-ip="<?php echo $_SERVER['REMOTE_ADDR']; ?>">使劲查询中，请稍候...</strong>
		<ul class="quick-menu clearfix">
			<li><a href="javascript:;" id="set-homepage">设为首页</a></li>
			<li><a href="javascript:;" id="add-favorite">添加收藏</a></li>
			<li><a href="http://www.adminunion.com/sitemap.xml" target="_blank">网站地图</a></li>
		</ul>
	</div>
</div>
<div class="header clearfix">
	<h1 class="logo"><a title="我的站长我的站！" href="http://www.adminunion.com/">站长军团</a></h1>
	<div class="widget">
		<a title="PageRank 值查询" href="http://pagerank.adminunion.com/adminunion.com"><img alt="" src="http://pagerank.adminunion.com/widget.php?size=xl&domain=adminunion.com"></a>
	</div>
	<div class="a712x60">
		<div class="a234x60">
<script>/*234*60，创建于2012-3-22*/ var cpro_id = 'u817092';</script><script src="http://cpro.baidu.com/cpro/ui/c.js"></script>
		</div>
		<div class="a468x60">
<script>/*468*60，创建于2012-3-5*/ var cpro_id = 'u794271';</script><script src="http://cpro.baidu.com/cpro/ui/c.js"></script>
		</div>
	</div>
</div>
<div class="content">
	<div class="toolbar clearfix">
		<div class="hd">
			<ul class="clearfix">
				<li class="current"><a href="http://www.adminunion.com">网站信息查询</a></li>
				<li><a href="http://www.mangguo.de/top-10-hosting/" target="_blank" class="red">美国十大主机推荐</a></li>
				<li><a href="http://a.adminunion.com/" target="_blank">站长学院</a></li>
				<li><a href="https://chrome.google.com/webstore/detail/jiciopodjlcihnefplabkfbeppfpfeib?hl=zh-CN" class="red" target="_blank">使用本站 Chrome 插件，一秒钟查询网站！</a></li>
				<li><a href="http://weibo.com/adminunion" target="_blank">新浪微博</a></li>
			</ul>
		</div>
		<div class="bd">
			<ul class="clearfix">
				<li<?php if($host == 'indexed') echo ' class="current"'; ?>><a href="http://indexed.adminunion.com/<?php echo $d; ?>">搜索引擎收录<b>√</b></a></li>
				<li<?php if($host == 'ip') echo ' class="current"'; ?>><a href="http://ip.adminunion.com/<?php echo $d; ?>">IP 反查域名<b>√</b></a></li>
				<li<?php if($host == 'pagerank') echo ' class="current"'; ?>><a href="http://pagerank.adminunion.com/<?php echo $d; ?>">PageRank 值<b>√</b></a></li>
				<li<?php if($host == 'keyword') echo ' class="current"'; ?>><a href="http://keyword.adminunion.com/<?php echo $d; ?>">关键词分析<b>√</b></a></li>
				<li<?php if($host == 'alexa') echo ' class="current"'; ?>><a href="http://alexa.adminunion.com/<?php echo $d; ?>">Alexa 排名<b>√</b></a></li>
				<li<?php if($host == 'link') echo ' class="current"'; ?>><a href="http://link.adminunion.com/<?php echo $d; ?>">友情链接检查<b>√</b></a></li>
				<li<?php if($host == 'spider') echo ' class="current"'; ?>><a href="http://spider.adminunion.com/<?php echo $d; ?>">蜘蛛模拟抓取<b>√</b></a></li>
				<li<?php if($host == 'ping') echo ' class="current"'; ?>><a href="http://ping.adminunion.com/<?php echo $d; ?>">Ping 通知<b>√</b></a></li>
				<li<?php if($host == 'whois') echo ' class="current"'; ?>><a href="http://whois.adminunion.com/<?php echo $d; ?>">域名 Whois<b>√</b></a></li>
				<li<?php if($host == 'icp') echo ' class="current"'; ?>><a href="http://icp.adminunion.com/<?php echo $d; ?>">ICP 网站备案<b>√</b></a></li>
			</ul>
		</div>
	</div>
	<div class="toolbar clearfix">
		<form class="searchform clearfix" method="get" action="/">
<?php if($host == 'keyword') { ?>
			<span class="s"><label for="s" class="hidden">请输入域名，如 adminunion.com</label><input type="text" id="s" name="domain" value="<?php echo $d; ?>" class="keyword-s210"><input type="text" name="keyword" value="<?php echo $keyword; ?>" class="keyword-s135"></span>
<?php } else { ?>
			<span class="s"><label for="s" class="hidden">请输入域名，如 adminunion.com</label><input type="text" id="s" name="domain" value="<?php echo $d; ?>"></span>
<?php } ?>
			<span class="searchsubmit"><button type="submit"></button></span>
			<span class="message"><blink>消息</blink>：<a href="https://chrome.google.com/webstore/detail/jiciopodjlcihnefplabkfbeppfpfeib?hl=zh-CN" target="_blank">使用本站 Chrome 插件，一秒钟查询网站！</a></span>
		</form>
		<div class="a468x60">
<script>/*468*60，创建于2012-3-8*/ var cpro_id = 'u799125';</script><script src="http://cpro.baidu.com/cpro/ui/c.js"></script>
		</div>
	</div>
	<div class="a950x76">
		<em class="col-sub">广告赞助</em>
		<ul class="col-main clearfix">
			<li><a href="http://www.bluehost.com/track/mangguo" class="green" target="_blank">BlueHost - 每月仅需 5.95 美元！</a></li>
			<li><a href="http://www.hostmonster.com/track/mangguo" class="blue" target="_blank">HostMonster - 每月仅需 5.95 美元！</a></li>
			<li><a href="http://www.mangguo.de/category/promotion/" target="_blank" class="red">Godaddy 域名注册优惠码信息</a></li>
			<li><a href="http://www.mangguo.org/hosting" target="_blank">芒果小站 - 美国主机推荐</a></li>
			<li><a href="http://stats.justhost.com/track?c38717e2731a3cc908b64aadd428b8aba" class="red" target="_blank">JustHost - 最佳博客主机选择，速度快 </a></li>
			<li><a href="https://my.hawkhost.com/aff.php?aff=3100" class="green" target="_blank">Hawkhost - 支付宝付款的美国主机</a></li>
			<li><a href="http://www.mangguo.de/top-10-hosting/" target="_blank">芒果主机 - 美国十大主机推荐</a></li>
			<li><a href="http://affiliate.godaddy.com/redirect/95B163F64AA934DC393CB79E3EF3602108CE0DCCF913CA81DECFD2130290BC91/?r=mangguo" class="red" target="_blank">Godaddy - 最佳低价主机 </a></li>
			<li><a href="http://secure.hostgator.com/cgi-bin/affiliates/clickthru.cgi?id=mangguo" class="red" target="_blank">HostGator - 值得信赖，每月 4.95 美元</a></li>
			<li><a href="http://www.mangguo.de/paypal-register-tutorial/" class="red" target="_blank">点此查看 PayPal 账户注册教程 </a></li>
			<li><a href="http://billing.meyu.net/aff.php?aff=010" class="blue" target="_blank">梦游科技，做有品质保证的主机</a></li>
			<li><a href="http://www.mangguo.de/" class="blue" target="_blank">芒果主机 - 提供专业美国主机导购信息</a></li>
		</ul>
	</div>
