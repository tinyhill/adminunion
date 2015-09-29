<?php

	$title = '网站 ' . $d.' 的PING通知服务';
	$keywords = '网站ping检测,ping值检测,ping检测软件,ping检测工具,ping检测网络,超级ping';
	$description = 'ping是基于XML_RPC标准协议的更新通告服务，用于在内容更新时通知搜索引擎及时进行抓取、更新的方式';

	require_once('header.php');

?>
	<div class="toolbox">
		<div class="hd"><h2>Ping 通知服务的功能说明</h2></div>
		<div class="bd">
			<div class="overview">Ping 是基于 XML_RPC 标准协议的更新通告服务，用于把网站内容更新快速通知给搜索引擎，以便搜索引擎及时进行抓取和更新。 </div>
		</div>
	</div>
	<div class="toolbox">
		<div class="hd"><h2>搜索引擎的响应结果</h2></div>
		<table class="bd ping-service">
			<tr>
				<td width="50%"><span class="ping-server">百度</span><span class="gray">http://ping.baidu.com/ping/RPC2</span></td>
				<td width="50%" class="ping-response" data-m="baidu"><span class="loading"></span></td>
			</tr>
			<tr>
				<td width="50%"><span class="ping-server">谷歌</span><span class="gray">http://blogsearch.google.com/ping/RPC2</span></td>
				<td width="50%" class="ping-response" data-m="google"><span class="loading"></span></td>
			</tr>
			<tr>
				<td width="50%"><span class="ping-server">雅虎</span><span class="gray">http://api.php.my.yahoo.com/RPC2</span></td>
				<td width="50%" class="ping-response" data-m="yahoo"><span class="loading"></span></td>
			</tr>
			<tr>
				<td width="50%"><span class="ping-server">有道</span><span class="gray">http://blog.youdao.com/ping/RPC2</span></td>
				<td width="50%" class="ping-response" data-m="youdao"><span class="loading"></span></td>
			</tr>
		</table>
	</div>
<?php

	$js = <<<EOT
<script>
	/**
	 * 逐个通知搜索引擎
	 */
	$('.ping-response').each(function () {
		var self = $(this);
		$.ajax({
			dataType: 'json',
			type: 'get',
			url: '/api.php',
			data: 'c=ping&m=' + self.attr('data-m') + '&d={$d}',
			success: function (d) {
				if (d.code == 200) {
					self.html('<span style="color:green;">通知成功</span>');
				}
				if (d.code == 400) {
					self.html('<span style="color:red;">通知失败</span>');
				}
			}
		});

	});
</script>
EOT;

	require_once('footer.php');

?>