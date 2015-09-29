<?php

	require_once(APPPATH . 'controller/ip/control.php');
	$address = json_decode(get_ip_address($d));
	$geolocation = json_decode(get_ip_geolocation($d));

	$title = $d . ' - IP 地址 ' . $address[0] . ' 位于 ' . $address[1] . ' - IP反查域名（同IP网站查询）';
	$keywords = 'ip地址反查域名,ip域名反查工具,ip查域名,域名反查,根据域名查ip,通过ip查域名,服务器 域名 反查,邮箱反查域名,怎么通过ip查域名,知道域名查ip';
	$description = '查找同个服务器里有多少个网站，同个IP反查域名数量';

	require_once('header.php');

?>
	<div class="toolbox">
		<div class="hd"><h2>域名&nbsp;&nbsp;<b class="red"><?php echo $d; ?></b>&nbsp;&nbsp;的 IP 反查域名查询结果</h2></div>
		<div class="bd">
			<div class="overview">IP 地址&nbsp;&nbsp;<big class="red"><?php echo $address[0]; ?></big>&nbsp;&nbsp;位于&nbsp;&nbsp;<b class="red"><?php echo $address[1]; ?></b></div>
		</div>
	</div>
	<div class="toolbox">
		<div class="hd"><h2>以下站点运行在此服务器上</h2></div>
		<table class="bd" id="ip-reverse">
			<tr>
				<td width="50%"><span class="loading"></span></td>
				<td width="50%"><span class="loading"></span></td>
			</tr>
		</table>
	</div>
	<div class="toolbox">
		<div class="hd"><h2>该 IP 在地图上的位置（纬度&nbsp;&nbsp;<b class="red"><?php echo $geolocation[0]; ?></b>，经度&nbsp;&nbsp;<b class="red"><?php echo $geolocation[1]; ?></b>）</h2></div>
		<div class="bd ip-gmap loading" id="ip-gmap"></div>
	</div>
<?php

	$js = <<<EOT
<script src="http://www.adminunion.com/assets/js/map.js"></script>
<script>
/**
 * 获取服务器上运行的站点
 */
$.ajax({
	type: 'get',
	dataType: 'json',
	url: '/api.php',
	data: 'c=ip&m=reverse&d={$d}',
	success: function (d) {
		var data = d.data;

		//设置站点列表
		var site = [];
		$.each(data, function (i, n) {
			if ((i + 1) % 2 === 1) {
				site.push('<tr><td width="50%"><a href="/' + n + '" title="' + n + '">' + n + '</a></td>');
			} else {
				site.push('<td width="50%"><a href="/' + n + '" title="' + n + '">' + n + '</a></td></tr>');
			}
		});

		//如果是奇数条数据，则补充空单元
		if (data.length % 2) {
			site.push('<td width="50%">&nbsp;</td></tr>');
		}
		$('#ip-reverse').html(site.join(''));
	}
});

/**
 * 定义谷歌地图加载函数
 * @method getMap
 * @param {Number} lat 纬度值
 * @param {Number} lng 经度值
 */
function getMap(lat, lng) {

	var latlng = new google.maps.LatLng(lat, lng),

		map = new google.maps.Map(document.getElementById('ip-gmap'), {
			zoom: 8,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}),

		marker = new google.maps.Marker({
			position: latlng,
			map: map
		});

}

//载入谷歌地图模块
getMap({$geolocation[0]}, {$geolocation[1]});
</script>
EOT;

	require_once('footer.php');

?>