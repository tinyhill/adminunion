<?php

	$title = '网站 ' . $d.' 的ICP备案信息查询';
	$keywords = 'icp备案查询,icp备案号,icp备案密码,icp备案查询网,icp备案是什么意思,icp备案证书号,工信部icp备案,icp备案是什么,icp备案流程,万网icp备案';
	$description = '提供网站icp备案查询,工业和信息化部ICP/IP地址/域名信息备案查询、工信部网站备案查询';

	require_once('header.php');

?>
	<div class="toolbox">
		<div class="hd"><h2>网站&nbsp;&nbsp;<b class="red"><?php echo $d; ?></b>&nbsp;&nbsp;的 ICP 备案信息查询结果</h2></div>
		<div class="bd">
			<div class="overview">网站&nbsp;&nbsp;<b class="red"><?php echo $d; ?></b>&nbsp;&nbsp;的 ICP 备案号为&nbsp;&nbsp;<big class="red" id="icp-main-license"><span class="loading"></span></big></div>
		</div>
	</div>
	<div class="toolbox">
		<div class="hd"><h2>该网站的详细备案信息（真实数据）</h2></div>
		<table class="bd" id="icp-data">
			<tr>
				<td width="50%">主办单位名称</td>
				<td width="50%" class="icp-data" data-type="unit_name"><span class="loading"></span></td>
			</tr>
			<tr>
				<td>主办单位性质</td>
				<td class="icp-data" data-type="unit_type"><span class="loading"></span></td>
			</tr>
			<tr>
				<td>网站备案/许可证号</td>
				<td class="icp-data" data-type="main_license"><span class="loading"></span></td>
			</tr>
			<tr>
				<td>网站名称</td>
				<td class="icp-data" data-type="site_name"><span class="loading"></span></td>
			</tr>
			<tr>
				<td>网站首页网址</td>
				<td class="icp-data" data-type="site_url"><span class="loading"></span></td>
			</tr>
			<tr>
				<td>审核时间</td>
				<td class="icp-data" data-type="verify_date"><span class="loading"></span></td>
			</tr>
		</table>
	</div>
<?php

	$js = <<<EOT
<script>
	/**
	 * 获取网站的 ICP 备案号
	 */
	$.ajax({
		type: 'get',
		dataType: 'json',
		url: '/api.php',
		data: 'c=icp&d={$d}',
		success: function (d) {
			//回填 ICP 备案号
			$('#icp-main-license').html(d['main_license'].replace(/-[0-9]*/g, ''));

			//网站首页网址换行处理
			d['site_url'] = d['site_url'].replace(/ /g, '<br>');

			//输出网站详细备案信息
			$('#icp-data td.icp-data').each(function () {
				var self = $(this);
					self.html(d[self.attr('data-type')]);
			});
		}
	});
</script>
EOT;

	require_once('footer.php');

?>