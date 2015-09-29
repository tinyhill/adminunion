<?php

	$title = '网站 ' . $d.' 的域名Whois查询';
	$keywords = '域名whois查询,域名查询工具whois,whois 批量查询,whois查询失败,whois信息查询,whois查询工具,whois,whois查询是什么,whois查询接口,whois历史查询';
	$description = '本站域名注册信息查询目前支持的国别或地区后缀查询几乎涵盖所有的域名后缀';

	require_once('header.php');

?>
	<div class="toolbox">
		<div class="hd"><h2>域名&nbsp;&nbsp;<b class="red"><?php echo $d; ?></b>&nbsp;&nbsp;相关后缀注册情况</h2></div>
		<table class="bd" id="whois-availability">
			<tr>
				<td width="12.5%" data-tld="com"><span class="loading"></span></td>
				<td width="12.5%" data-tld="net"><span class="loading"></span></td>
				<td width="12.5%" data-tld="org"><span class="loading"></span></td>
				<td width="12.5%" data-tld="info"><span class="loading"></span></td>
				<td width="12.5%" data-tld="name"><span class="loading"></span></td>
				<td width="12.5%" data-tld="cc"><span class="loading"></span></td>
				<td width="12.5%" data-tld="la"><span class="loading"></span></td>
				<td width="12.5%" data-tld="me"><span class="loading"></span></td>
			</tr>
		</table>
	</div>
	<div class="toolbox">
		<div class="hd"><h2>该域名的 Whois 查询结果</h2></div>
		<div class="bd whois-data" id="whois-data"><span class="loading"></span></div>
	</div>
<?php

	$js = <<<EOT
<script>
	/**
	 * 获取域名的 Whois 数据
	 */
	$.ajax({
		type: 'get',
		url: '/api.php',
		data: 'c=whois&m=whois&d={$d}',
		success: function (d) {
			d && $('#whois-data').html(d);
		}
	});

	/**
	 * 查询域名相关的可注册后缀
	 */
	$('#whois-availability td').each(function () {
		var self = $(this);
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: '/api.php',
			data: 'c=whois&m=availability&d={$d}&tld=' + self.attr('data-tld'),
			success: function (d) {
				if (d.status) {
					self.html('<a target="_blank" href="http://affiliate.godaddy.com/redirect/95B163F64AA934DC393CB79E3EF3602108CE0DCCF913CA81DECFD2130290BC91/?r=mangguo" style="green" title="去 Godaddy 注册这个域名"><b>.' + self.attr('data-tld').toUpperCase() + '</b> 未注</a>');
				} else {
					self.html('<a target="_blank" href="http://affiliate.godaddy.com/redirect/95B163F64AA934DC393CB79E3EF3602108CE0DCCF913CA81DECFD2130290BC91/?r=mangguo" class="red" title="去 Godaddy 注册这个域名"><b>.' + self.attr('data-tld').toUpperCase() + '</b> 已注</a>');
				}
			}
		});
	});
</script>
EOT;

	require_once('footer.php');

?>