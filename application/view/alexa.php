<?php

	$title = '网站 ' . $d.' 的alexa排名查询,alexa排名,alexa网站排名,alexa网站排名查询_站长军团';
	$keywords = 'alexa排名查询,alexa排名助手,alexa全球排名,提高alexa排名,alexa的排名控件,alexa网站排名,alexa排名优化,alexa排名提交,alexa排名是什么,alexa排名有什么用';
	$description = '提供alexa网站排名、流量、访问量、页面浏览量查询等专业的中文网站排名查询服务';

	require_once('header.php');

?>
	<div class="toolbox">
		<div class="hd"><h2>网站&nbsp;&nbsp;<b class="red"><?php echo $d; ?></b>&nbsp;&nbsp;Alexa 排名查询结果</h2></div>
		<table class="bd">
			<tr>
				<td colspan="8" class="overview">网站&nbsp;&nbsp;<b class="red" id="alexa-site-name"><span class="loading"></span></b>&nbsp;&nbsp;在 Alexa 上排名第&nbsp;&nbsp;<big class="red" id="alexa-site-rank"><span class="loading"></span></big>&nbsp;&nbsp;位，地区排名第&nbsp;&nbsp;<b class="red" id="alexa-country-rank"><span class="loading"></span></b>&nbsp;&nbsp;位，共有&nbsp;&nbsp;<b class="red" id="alexa-link-in"><span class="loading"></span></b>&nbsp;&nbsp;个反链</td>
			</tr>
			<tr>
				<th width="12.5%">当日排名</th>
				<th width="12.5%">排名变化趋势</th>
				<th width="12.5%">一周平均排名</th>
				<th width="12.5%">排名变化趋势</th>
				<th width="12.5%">一月平均排名</th>
				<th width="12.5%">排名变化趋势</th>
				<th width="12.5%">三月平均排名</th>
				<th width="12.5%">排名变化趋势</th>
			</tr>
			<tr id="alexa-rank">
				<td data-type="d1"><span class="loading"></span></td>
				<td data-type="d1"><span class="loading"></span></td>
				<td data-type="d7"><span class="loading"></span></td>
				<td data-type="d7"><span class="loading"></span></td>
				<td data-type="m1"><span class="loading"></span></td>
				<td data-type="m1"><span class="loading"></span></td>
				<td data-type="m3"><span class="loading"></span></td>
				<td data-type="m3"><span class="loading"></span></td>
			</tr>
			<tr>
				<th colspan="4" width="50%">日均&nbsp;&nbsp;<b class="red">IP</b>&nbsp;&nbsp;访问量（一月平均）</th>
				<th colspan="4" width="50%">日均&nbsp;&nbsp;<b class="red">PV</b>&nbsp;&nbsp;浏览量（一月平均）</th>
			</tr>
			<tr id="alexa-traffic">
				<td colspan="4" data-type="uv"><span class="loading"></span></td>
				<td colspan="4" data-type="pv"><span class="loading"></span></td>
			</tr>
			<tr>
				<th colspan="4" width="50%">被访问子站点</th>
				<th colspan="4" width="50%">子站点访问比例（倒序）</th>
			</tr>
			<tr id="alexa-subsite">
				<td colspan="4"><span class="loading"></span></td>
				<td colspan="4"><span class="loading"></span></td>
			</tr>
			<tr class="alexa-graph-nav" id="alexa-graph-nav">
				<th colspan="2" width="20%"><a data-type="6m" href="javascript:;" class="current">六个月平均排名曲线</a></th>
				<th colspan="2" width="20%"><a data-type="3m" href="javascript:;">三个月平均排名曲线</a></th>
				<th colspan="2" width="20%"><a data-type="1m" href="javascript:;">一个月平均排名曲线</a></th>
				<th colspan="2" width="20%"><a data-type="7.0m" href="javascript:;">一星期平均排名曲线</a></th>
			</tr>
			<tr class="alexa-graph-content" id="alexa-graph-content">
				<td colspan="8">
					<ul class="clearfix loading">
						<li data-type="6m"><img src="http://traffic.alexa.com/graph?w=928&h=300&r=6m&y=t&u=<?php echo $d; ?>" alt=""></li>
						<li data-type="3m"><img src="http://traffic.alexa.com/graph?w=928&h=300&r=3m&y=t&u=<?php echo $d; ?>" alt=""></li>
						<li data-type="1m"><img src="http://traffic.alexa.com/graph?w=928&h=300&r=1m&y=t&u=<?php echo $d; ?>" alt=""></li>
						<li data-type="7.0m"><img src="http://traffic.alexa.com/graph?w=928&h=300&r=7.0m&y=t&u=<?php echo $d; ?>" alt=""></li>
					</ul>
				</td>
			</tr>
		</table>
	</div>
<?php

	$js = <<<EOT
<script>
/**
 * 查询 Alexa 基础信息
 */
$.ajax({
	dataType: 'json',
	type: 'get',
	url: '/api.php',
	data: 'c=alexa&m=summary&d={$d}',
	success: function (d) {
		$('#alexa-site-name').html(d.site_name);
		$('#alexa-site-rank').html(d.site_rank);
		$('#alexa-link-in').html(d.link_in);
	}
});

/**
 * 查询 Alexa 详细信息
 */
$.ajax({
	dataType: 'json',
	type: 'get',
	url: '/api.php',
	data: 'c=alexa&m=siteinfo&d={$d}',
	success: function (d) {

		//获取地区排名和反链数据
		$('#alexa-country-rank').html(d.summary.country_rank);

		//获取排名和变化趋势数据
		$('#alexa-rank td:even').each(function () {
			$(this).html(d.rank[$(this).attr('data-type')][0]);
		});
		$('#alexa-rank td:odd').each(function () {
			var delta = d.rank[$(this).attr('data-type')][1];
			if (parseInt(delta) && parseInt(delta) < 0) {
				delta = '<span style="color:green;">上升&nbsp;' + Math.abs(delta) + '</span>';
			}
			if (parseInt(delta) && parseInt(delta) > 0) {
				delta = '<span style="color:red;">下降&nbsp;' + Math.abs(delta) + '</span>';
			}
			$(this).html(delta);
		});

		//获取日均 IP 访问量、日均 PV 浏览量数据
		$('#alexa-traffic td').each(function (i) {
			if (i == 0) {
				$(this).html('<b class="red">≈ ' + d.traffic[$(this).attr('data-type')] + '</b>');
			}
			else {
				$(this).html('≈ ' + d.traffic[$(this).attr('data-type')]);
			}
		});

		//获取被访问子站点数据
		var subsite = [];
		$.each(d.subsite, function (i, n) {
				subsite.push('<tr><td colspan="4">' + n.domain + '</td><td colspan="4">' + n.percent + '</td></tr>');
		});
		$('#alexa-subsite').after(subsite.join(''));
		$('#alexa-subsite').remove();
	}
});

/**
 * 获取 Alexa 全球排名趋势数据
 */
$('#alexa-graph-nav a').click(function () {
	$('#alexa-graph-nav th a').removeClass('current');
	$(this).addClass('current');
	$('#alexa-graph-content li').hide();
	$('#alexa-graph-content').find('li[data-type=' + $(this).attr('data-type') + ']').show();
});
</script>
EOT;

	require_once('footer.php');

?>