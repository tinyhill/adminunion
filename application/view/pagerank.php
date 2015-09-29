<?php

	$title = '网站 ' . $d.' 的PageRank值、PR值真假、PR劫持查询';
	$keywords = '网站pr值查询,百度pr值查询,pr输出值查询,pr值批量查询,如何查询pr值,域名pr值查询,pr值,谷歌pr值查询,pr输出值,网站pr值';
	$description = 'Pr是Google用来标识网页等级的一种方法，Pr值越高说明Google对该网址的评级越高。pr值查询工具是网站运营人员了解网站质量、交换友情链接的必备工具之一';

	require_once('header.php');

?>
	<div class="toolbox">
		<div class="hd"><h2>网站&nbsp;&nbsp;<b class="red"><?php echo $d; ?></b>&nbsp;&nbsp;的 PageRank 值查询结果</h2></div>
		<div class="bd">
			<div class="overview">网站&nbsp;&nbsp;<b class="red"><?php echo $d; ?></b>&nbsp;&nbsp;的 PageRank 值为&nbsp;&nbsp;<big class="red" id="pagerank-pagerank"><span class="loading"></span></big>&nbsp;，该网站的 PR 是&nbsp;&nbsp;<b class="red" id="pagerank-validate"><span class="loading"></span></b>&nbsp;&nbsp;的</div>
		</div>
	</div>
	<div class="toolbox">
		<div class="hd"><h2>从 Google 678 个数据中心随机抽样查询 PR</h2></div>
		<table class="bd" id="pagerank-datacenter">
<?php

	$datacenter = @file_get_contents(APPPATH . 'controller/pagerank/datacenter.dat');
	$datacenter = json_decode($datacenter, true);
	for($i = 0; $i < 3; $i++){
		echo '			<tr>' . "\n";
		echo '				<td width="50%">' . $datacenter[rand(0, 119)] . '</td>' . "\n";
		echo '				<td width="50%" class="pagerank-datacenter"><span class="loading"></span></td>' . "\n";
		echo '			</tr>' . "\n";
	}

?>
		</table>
	</div>
	<div class="toolbox">
		<div class="hd"><h2>PageRank 值挂件调用</h2></div>
		<table class="bd pagerank-widget">
			<tr>
				<th width="198"><img src="http://pagerank.adminunion.com/widget.php?size=s&domain=<?php echo $d; ?>" alt=""></th>
				<td><span>&lt;a href="http://pagerank.adminunion.com/<?php echo $d; ?>" title="PageRank 值查询"&gt;&lt;img src="http://pagerank.adminunion.com/widget.php?size=s&domain=<?php echo $d; ?>" alt=""&gt;&lt;/a&gt;</span></td>
			</tr>
			<tr>
				<th><img src="http://pagerank.adminunion.com/widget.php?size=m&domain=<?php echo $d; ?>" alt=""></th>
				<td><span>&lt;a href="http://pagerank.adminunion.com/<?php echo $d; ?>" title="PageRank 值查询"&gt;&lt;img src="http://pagerank.adminunion.com/widget.php?size=m&domain=<?php echo $d; ?>" alt=""&gt;&lt;/a&gt;</span></td>
			</tr>
			<tr>
				<th><img src="http://pagerank.adminunion.com/widget.php?size=l&domain=<?php echo $d; ?>" alt=""></th>
				<td><span>&lt;a href="http://pagerank.adminunion.com/<?php echo $d; ?>" title="PageRank 值查询"&gt;&lt;img src="http://pagerank.adminunion.com/widget.php?size=l&domain=<?php echo $d; ?>" alt=""&gt;&lt;/a&gt;</span></td>
			</tr>
			<tr>
				<th><img src="http://pagerank.adminunion.com/widget.php?size=xl&domain=<?php echo $d; ?>" alt=""></th>
				<td><span>&lt;a href="http://pagerank.adminunion.com/<?php echo $d; ?>" title="PageRank 值查询"&gt;&lt;img src="http://pagerank.adminunion.com/widget.php?size=xl&domain=<?php echo $d; ?>" alt=""&gt;&lt;/a&gt;</span></td>
			</tr>
		</table>
	</div>
<?php

	$js = <<<EOT
<script>
/**
 * 获取域名的 PageRank 值
 */
$.ajax({
	type: 'get',
	url: '/api.php',
	data: 'c=pagerank&m=pagerank&d={$d}',
	success: function(d){
		$('#pagerank-pagerank').html(d);
	}
});

/**
 * 获取 PageRank 的真实性
 */
$.ajax({
	type: 'get',
	url: '/api.php',
	data: 'c=pagerank&m=validate&d={$d}',
	success: function(d){
		$('#pagerank-validate').html(d);
	}
});

/**
 * 从 Google 678 个数据中心查询 PR
 */
$('#pagerank-datacenter td.pagerank-datacenter').each(function(){
	var self = $(this);
	$.ajax({
		type: 'get',
		url: '/api.php',
		data: 'c=pagerank&m=pagerank&d={$d}',
		success: function(d) {
			self.html(d);
		}
	});
});
</script>
EOT;

	require_once('footer.php');

?>