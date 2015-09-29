<?php

/**
 * 查询 Alexa 概要信息
 */
function get_alexa_summary ($d) {

	$content = @file_get_contents('http://data.alexa.com/data?cli=10&dat=snba&ver=7.0&cdt=alx_vw=20&url=' . $d) or die('{"site_name":"' . $d . '","alexa_rank":"0"}');

	//网站名称
	if (preg_match('/TITLE TEXT=\"(.*?)\"/i', $content, $match)) {
		$site_name = $match[1];
	} else {
		$site_name = $d;
	}

	//全球排名
	if (preg_match('/<POPULARITY URL=\"(.*?)\" TEXT=\"(.*?)\"/i', $content, $match)) {
		$site_rank = $match[2];
	} else {
		$site_rank = '<span style="font-size:12px;">暂无数据</span>';
	}

	//反链数量
	if (preg_match('/<LINKSIN NUM=\"(.*?)\"/i', $content, $match)) {
		$link_in = $match[1];
	} else {
		$link_in = '0';
	}

	//Reach 值：每一百万网民中有多少人访问目标网站
	if (preg_match('/<REACH RANK=\"(.*?)\"/i', $content, $match)) {
		$reach_rank = $match[1];
	} else {
		$reach_rank = '0';
	}

	//三月平均排名变化
	if (preg_match('/<RANK DELTA=\"(.*?)\"/i', $content, $match)) {
		$rank_delta = $match[1];
	} else {
		$rank_delta = '0';
	}

	return json_encode(array(
		'site_name' => $site_name,
		'site_rank' => $site_rank,
		'link_in' => $link_in,
		'reach_rank' => $reach_rank,
		'rank_delta' => $rank_delta
	));

}

/**
 * 获取 Alexa 站点数据
 */
function get_alexa_siteinfo ($d) {

	$siteinfo = array(
		'summary' => array('site_name' => $d, 'global_rank' => '0', 'country_rank' => '0', 'link_in' => '0'),
		'rank' => array('d1' => array('无', '无'), 'd7' => array('无', '无'), 'm1' => array('无', '无'), 'm3' => array('无', '无')),
		'traffic' => array('uv' => '相关数据不充分，无法统计', 'pv' => '相关数据不充分，无法统计'),
		'subsite' => array(array('domain' => '无', 'percent' => '无'))
	);

	$content = @file_get_contents('http://www.alexa.com/siteinfo/' . $d) or die(json_encode($siteinfo));
	$content = str_replace(array("\r\n", "\r", "\n"), '', $content);

	//网站基本信息
	if(preg_match('/id=\"siteinfo-site-summary\"(.*?)id=\"siteinfotabs\"/', $content, $match)){
		$match_summary = $match[1];
		if(preg_match('/<p style=\"margin-left: 48px;\">(.*)&nbsp;<\/p>/', $match_summary, $site_name)){
			$siteinfo['summary']['site_name'] = str_replace(',', '', $site_name[1]);
		}
		if(preg_match('/alt=\"Global\" style=\"margin-bottom:-2px;\"\/>(.*)		                    			            <\/div>/', $match_summary, $global_rank)){
			$siteinfo['summary']['global_rank'] = str_replace(',', '', $global_rank[1]);
		}
		if(preg_match('/Flag\"\/>(.*)			              	<\/div><div class=\"label\">Rank in/', $match_summary, $country_rank)) {
			$siteinfo['summary']['country_rank'] = str_replace(',', '', $country_rank[1]);
		}
		if(preg_match('/<a class=\"data-color2 \" href=\"\/site\/linksin\/(.*)">(.*)								<\/a><\/div>/', $match_summary, $link_in)) {
			$siteinfo['summary']['link_in'] = str_replace(',', '', $link_in[2]);
		}
	}

	//排名及变化趋势
	if(preg_match('/id=\"rank\"(.*)id=\"pageviews\"/', $content, $match)){
		$match_rank = $match[1];
		if (preg_match('/<th>Yesterday<\/th><td class=\"avg \">(.*?)<\/td><td class=\"percent \">(.*?) <\/td>/', $match_rank, $d1)) {
			$siteinfo['rank']['d1'] = array(str_replace(',', '', $d1[1]), str_replace(',', '', $d1[2]));
		}
		if (preg_match('/<th>7 day<\/th><td class=\"avg \">(.*?)<\/td><td class=\"percent \">(.*?) <\/td>/', $match_rank, $d7)) {
			$siteinfo['rank']['d7'] = array(str_replace(',', '', $d7[1]), str_replace(',', '', $d7[2]));
		}
		if (preg_match('/<th>1 month<\/th><td class=\"avg \">(.*?)<\/td><td class=\"percent \">(.*?) <\/td>/', $match_rank, $m1)) {
			$siteinfo['rank']['m1'] = array(str_replace(',', '', $m1[1]), str_replace(',', '', $m1[2]));
		}
		if (preg_match('/<th>3 month<\/th><td class=\"avg \">(.*?)<\/td><td class=\"percent \">(.*?) <\/td>/', $match_rank, $m3)) {
			$siteinfo['rank']['m3'] = array(str_replace(',', '', $m3[1]), str_replace(',', '', $m3[2]));
		}
	}

	//日均 IP 访问量
	if(preg_match('/id=\"reach\"(.*)id=\"bounce\"/', $content, $match)){
		if (preg_match('/<th>1 month<\/th><td class=\"avg \">(.*?)<\/td>/', $match[1], $match)) {
			$siteinfo['traffic']['uv'] = round($match[1] * 545454 * 10);
		}
	}

	//日均 PV 浏览量
	if(preg_match('/id=\"pageviews_per_user\"(.*)id=\"time_on_site\"/', $content, $match)) {
		if (preg_match('/<th>1 month<\/th><td class=\"avg \">(.*?)<\/td>/', $match[1], $match)) {
			$siteinfo['traffic']['pv'] = round($match[1] * $siteinfo['traffic']['uv']);
		}
	}

	//被访问子站点
	if(preg_match('/id=\"where-visitors-go\" style=\"margin-left:20px;\">(.*)<h2>Audience Snapshot<\/h2>/', $content, $match)){
		if (preg_match_all('/<p class=\"tc1\" style=\"width:70%\">(.*?)<\/p><p class=\"tc1\" style=\"width:30%; text-align:right;\">(.*?)<\/p>/', $match[1], $match)) {
			$siteinfo['subsite'] = array();
			foreach ($match[1] as $k => $v) {
				array_push($siteinfo['subsite'], array('domain' => $v, 'percent' => $match[2][$k]));
			}
		}
	}

	return json_encode($siteinfo);

}

?>